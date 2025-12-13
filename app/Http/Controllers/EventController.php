<?php

namespace App\Http\Controllers;

use App\Models\Analysys;
use App\Models\Event;
use App\Models\Type;
use App\Models\Classification;
use App\Models\ids_agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use App\Events\EventCreated;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Schema\Blueprint;
use App\Traits\FieldNameValidator;
use App\Http\Controllers\EventAttributeController;
use App\Http\Controllers\LlmController;

class EventController extends BaseController
{
    private $qtdEvent = 100;
    use FieldNameValidator;

    public function __construct(Event $event)
    {
        parent::__construct($event);
    }

    public function store(Request $request): JsonResponse
    {
        if (config("system_settings.use_smart_detector_ia") == 'Yes') {
            $analyze = LlmController::analyzeIa($request);
            if ($analyze['status'] == 'success') {
                $request->merge([
                    'intrusion_normal' => $analyze['intrusion_normal'],
                    'analysys' => $analyze['analysys']
                ]);
            }
        }
        if (config("system_settings.all_events") == 'No' && (!$request->intrusion_normal || $request->intrusion_normal == '')) {
            $request->merge([
                'intrusion_normal' => 'Intrusion',
            ]);
        }
        $validateIntrusionField = $this->validateIntrusionField($request);
        if ($validateIntrusionField['valid'] == false) {
            return parent::responseGeneric($validateIntrusionField['message'], 401, 'error');
        }
        if ($request->classification) {
            if (config("system_settings.all_events") == 'Yes' && $request->intrusion_normal == 'Normal') {
                $request->request->remove('classification');
            } else {
                $classification_id = Classification::where('description', $request->classification)->value('id');
                if ($classification_id) {
                    $request->merge([
                        'classifications_id' => $classification_id,
                    ]);
                    $request->request->remove('classification');
                } else {
                    return parent::responseGeneric('Classificação informada não foi encontrada no sistema, tente novamente.', 401, 'error');
                }
            }
        }
        if ($request->analysys) {
            $analysys_id = Analysys::create(['description' => $request->analysys])->id;
            $request->merge([
                'analysys_id' => $analysys_id,
            ]);
            $request->request->remove('analysys');
        }
        if ($request->type) {
            $type_id = Type::where('description', $request->type)->value('id');
            if (!$type_id) {
                $type_id = Type::create(['description' => $request->type])->id;
            }
            $request->merge([
                'types_id' => $type_id,
            ]);
            $request->request->remove('type');
        }
        $disabledFields = EventAttributeController::getDisabledFields();
        foreach ($disabledFields as $df) {
            $request->request->remove($df);
        }
        try {
            $event = $this->model->create($request->all());
        } catch (QueryException $e) {
            if ($e->errorInfo[1] === 1054) {
                return parent::responseGeneric('Uma ou mais colunas informadas não existem na tabela. Verifique novamente os campos que você está enviando', 500, 'error');
            } else {
                return parent::responseGeneric('Não foi possível salvar os dados, verifique se todos os campos obrigatórios foram preenchidos corretamente', 500,'error');
            }
        }
        $event->load(['classification', 'analysys', 'type', 'idsAgent']);
        event(new EventCreated($event));
        return parent::response($event);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        $data = $this->model
                    ->with('classification')
                    ->with('analysys')
                    ->with('type')
                    ->with('idsAgent')
                    ->whereDate('event_date_time', Carbon::today())
                    ->orderBy('event_date_time', 'desc')
                    ->take($this->qtdEvent)
                    ->get();
        return parent::responseGeneric($data);
    }

    public function show(int $id = null)
    {
        return view('/event');
    }

    public function display()
    {
        return view('/events');
    }

    public function get(int $id)
    {
        $event = $this->model
                    ->with('classification')
                    ->with('analysys')
                    ->with('type')
                    ->with('idsAgent')
                    ->find($id);
        return parent::response($event);
    }

    public function getAll(Request $request, array $attributes = null): JsonResponse
    {
        return parent::index($request, ['event_date_time', 'desc']);
    }

    public function paginate(Request $request, int $qtd = null, array $order): JsonResponse
    {
        $by = $order[0];
        $direction = $order[1];
        $qtd = $qtd ?? 20;
        $data = [];
        if($request->has('filter')) {
            $this->filter($request->filter);
        }
        $data = $this->model
                    ->with('classification')
                    ->with('analysys')
                    ->with('type')
                    ->with('idsAgent')
                    ->orderby($by, $direction)
                    ->paginate($qtd);
        return parent::responseGeneric($data);
    }

    public function getDashboards(Request $request): JsonResponse
    {
        $from = $request->query('from');
        $to   = $request->query('to');
        $ids  = $request->query('ids');
        $data = [
            'totalEvents' => $this->getTotalEvents($from, $to, $ids),
            'totalIntrusions' => $this->getTotalIntrusionsNormal($from, $to, $ids, 'Intrusion'),
            'totalNormal' => $this->getTotalIntrusionsNormal($from, $to, $ids, 'Normal'),
            'totalsByDay' => $this->getTotalsByDay($from, $to, $ids),
            'classifications'  => $this->getTotalsByClassification($from, $to, $ids),
            'types' => $this->getTotalsByTypes($from, $to, $ids)
        ];
        return response()->json($data, 201);
    }

    public static function addTableColumn(string $field, string $type_field = 'text'): bool
    {
        try {
            self::validateFieldName($field);
            if (!Schema::hasColumn('events', $field)) {
                Schema::table('events', function (Blueprint $table) use ($field, $type_field) {
                    if ($type_field === 'textarea') {
                        $table->text($field)->nullable();
                    } else {
                        $table->string($field, 255)->nullable();
                    }
                });
                return true;
            } else {
                Log::info("The column '{$field}' has already exists in the table 'events'.");
                return false;
            }
        } catch (\Throwable $e) {
            Log::error("Error to add column '{$field}': " . $e->getMessage());
            return false;
        }
    }

    public static function removeColumn(string $field): void
    {
        try {
            self::validateFieldName($field);
            if (Schema::hasColumn('events', $field)) {
                Schema::table('events', function (Blueprint $table) use ($field) {
                    $table->dropColumn($field);
                });
            } else {
                Log::info("The column '{$field}' doesn't exist in the table 'events'.");
            }
        } catch (\Throwable $e) {
            Log::error("Error to remove column '{$field}': " . $e->getMessage());
        }
    }

    public static function updateColumn(string $type_field, string $field_name): void
    {
        try {
            if (Schema::hasColumn('events', $field_name)) {
                Schema::table('events', function (Blueprint $table) use ($field_name, $type_field) {
                    if ($type_field === 'textarea') {
                        Schema::table('events', function (Blueprint $table) use ($field_name) {
                            $table->text($field_name)->change();
                        });
                    } elseif ($type_field === 'text') {
                        Schema::table('events', function (Blueprint $table) use ($field_name) {
                            $table->string($field_name, 255)->change();
                        });
                    }
                });
            } else {
                Log::info("The column '{$field_name}' doesn't exist in the table 'events'.");
            }
        } catch (\Throwable $e) {
            Log::error("Error to update column '{$field_name}': " . $e->getMessage());
        }
    }

    private function validateIntrusionField(Request $request): array
    {
        $intrusionField = $request->intrusion_normal ? ucfirst($request->intrusion_normal) : null;
        if ($intrusionField == null && config("system_settings.all_events") == 'Yes' && config("system_settings.use_smart_detector_ia") == 'No') {
            return [
                'valid' => false,
                'message' => 'Campo intrusion_normal não foi informado. Ele dever ser "Normal" ou "Intrusion"'
            ];
        }
        if ($intrusionField && ($intrusionField != 'Normal' && $intrusionField != 'Intrusion' && config("system_settings.all_events") == 'Yes' && config("system_settings.use_smart_detector_ia") == 'No')) {
            return [
                'valid' => false,
                'message' => 'Valor para o campo intrusion_normal inválido. Ele dever ser "Normal" ou "Intrusion"'
            ];
        }

        if ($intrusionField != 'Intrusion' && config("system_settings.all_events") == 'No') {
            return [
                'valid' => false,
                'message' => '"Ativar Recebimento de Todos os Eventos" está desativado no sistema, portanto o valor informado para o campo intrusion_normal é inválido. Deixe ele em branco ou informe "Intrusion"'
            ];
        }
        return [
            'valid' => true
        ];
    }

    private function getTotalEvents($from = null, $to = null, $ids = null): int
    {
        $count = $this->model->query()
            ->when(!empty($from), function ($query) use ($from) {
                $query->where('event_date_time', '>=', $from);
            })
            ->when(!empty($to), function ($query) use ($to) {
                $query->where('event_date_time', '<=', $to);
            })
            ->when(!empty($ids), function ($query) use ($ids) {
                $query->where('ids_id', $ids);
            })
            ->count();
        return $count;
    }

    private function getTotalIntrusionsNormal($from = null, $to = null, $ids = null, $intrusion_normal): int
    {
        $count = $this->model->query()
            ->when(!empty($from), function ($query) use ($from) {
                $query->where('event_date_time', '>=', $from);
            })
            ->when(!empty($to), function ($query) use ($to) {
                $query->where('event_date_time', '<=', $to);
            })
            ->when(!empty($ids), function ($query) use ($ids) {
                $query->where('ids_id', $ids);
            })
            ->where('intrusion_normal', $intrusion_normal)
            ->count();
        return $count;
    }

    private function getTotalsByDay($from = null, $to = null, $ids = null)
    {
        return $this->model->query()
            ->when(!empty($from), function ($query) use ($from) {
                $query->where('event_date_time', '>=', $from);
            })
            ->when(!empty($to), function ($query) use ($to) {
                $query->where('event_date_time', '<=', $to);
            })
            ->when(!empty($ids), function ($query) use ($ids) {
                $query->where('ids_id', $ids);
            })
            ->selectRaw("
                DATE(event_date_time) as day,
                COUNT(*) as totalEvents,
                SUM(CASE WHEN intrusion_normal = 'Intrusion' THEN 1 ELSE 0 END) as totalIntrusions,
                SUM(CASE WHEN intrusion_normal = 'Normal' THEN 1 ELSE 0 END) as totalNormal
            ")
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();
    }

    private function getTotalsByClassification($from = null, $to = null, $ids = null)
    {
        return $this->model->query()
            ->join('classifications', 'events.classifications_id', '=', 'classifications.id')
            ->when(!empty($from), function ($query) use ($from) {
                $query->where('events.event_date_time', '>=', $from);
            })
            ->when(!empty($to), function ($query) use ($to) {
                $query->where('events.event_date_time', '<=', $to);
            })
            ->when(!empty($ids), function ($query) use ($ids) {
                $query->where('events.ids_id', $ids);
            })
            ->selectRaw('
                classifications.description as description,
                COUNT(events.id) as total
            ')
            ->groupBy('classifications.description')
            ->pluck('total', 'description'); 
    }

    private function getTotalsByTypes($from = null, $to = null, $ids = null)
    {
        return $this->model->query()
            ->join('types', 'events.types_id', '=', 'types.id')
            ->when(!empty($from), function ($query) use ($from) {
                $query->where('events.event_date_time', '>=', $from);
            })
            ->when(!empty($to), function ($query) use ($to) {
                $query->where('events.event_date_time', '<=', $to);
            })
            ->when(!empty($ids), function ($query) use ($ids) {
                $query->where('events.ids_id', $ids);
            })
            ->where('events.intrusion_normal', '=', 'Intrusion')
            ->selectRaw('
                types.description as description,
                COUNT(events.id) as total
            ')
            ->groupBy('types.description')
            ->pluck('total', 'description'); 
    }
}

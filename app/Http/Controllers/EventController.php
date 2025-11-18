<?php

namespace App\Http\Controllers;

use App\Models\Analysys;
use App\Models\Event;
use App\Models\Type;
use App\Models\Classification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Events\EventCreated;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Schema\Blueprint;
use App\Traits\FieldNameValidator;
use App\Http\Controllers\EventAttributeController;

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
        $event = $this->model->create($request->all());
        $event->load(['classification', 'analysys', 'type']);
        event(new EventCreated($event));
        return parent::response($event);
    }

    public function index(Request $request, array $attributes = null): JsonResponse
    {
        $data = $this->model
                    ->with('classification')
                    ->with('analysys')
                    ->with('type')
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
                    ->orderby($by, $direction)
                    ->paginate($qtd);
        return parent::responseGeneric($data);
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
        if ($intrusionField == null && config("system_settings.all_events") == 'Yes') {
            return [
                'valid' => false,
                'message' => 'Campo intrusion_normal não foi informado. Ele dever ser "Normal" ou "Intrusion"'
            ];
        }
        if ($intrusionField && ($intrusionField != 'Normal' && $intrusionField != 'Intrusion' && config("system_settings.all_events") == 'Yes')) {
            return [
                'valid' => false,
                'message' => 'Valor para o campo intrusion_normal inválido. Ele dever ser "Normal" ou "Intrusion"'
            ];
        }

        if ($intrusionField != 'Intrusion' && config("system_settings.all_events") == 'No') {
            return [
                'valid' => false,
                'message' => '"Ativar Recebimento de Todos os Eventos" está desativado no sistema, portanto o valor "Normal" para o campo intrusion_normal é inválido. Deixe ele em branco ou informe "Intrusion"'
            ];
        }
        return [
            'valid' => true
        ];
    }
}

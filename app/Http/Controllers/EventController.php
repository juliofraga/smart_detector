<?php

namespace App\Http\Controllers;

use App\Models\Analysys;
use App\Models\Event;
use App\Models\Type;
use App\Models\Classification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends BaseController
{
    private $qtdEvent = 100;

    public function __construct(Event $event)
    {
        parent::__construct($event);
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->classification) {
            $classification_id = Classification::where('description', $request->classification)->value('id');
            if ($classification_id) {
                $request->merge([
                    'classifications_id' => $classification_id,
                ]);
            } else {
                return parent::responseGeneric('Classificação informada não foi encontrada no sistema, tente novamente.', 401, 'error');
            }
        }
        if ($request->analysys) {
            $analysys_id = Analysys::create(['description' => $request->analysys])->id;
            $request->merge([
                'analysys_id' => $analysys_id,
            ]);
        }
        if ($request->type) {
            $type_id = Type::where('description', $request->type)->value('id');
            if (!$type_id) {
                $type_id = Type::create(['description' => $request->type])->id;
            }
            $request->merge([
                'types_id' => $type_id,
            ]);
        }
        return parent::store($request);
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

    public function getNewEvents(int $id): JsonResponse
    {
        $data = $this->model
                    ->with('classification')
                    ->with('analysys')
                    ->with('type')
                    ->where('id', '>', $id)
                    ->get();
        if ($data) {
            return parent::responseGeneric($data);
        } else {
            return parent::responseGeneric('Sem novos registros');
        }
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
}

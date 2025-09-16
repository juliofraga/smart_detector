<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends AbstractController
{
    private $qtdEvent = 100;

    public function __construct(Event $event)
    {
        parent::__construct($event);
    }

    public function index(Request $request): JsonResponse
    {
        $data = $this->model
                    ->whereDate('event_date_time', Carbon::today())
                    ->orderBy('event_date_time', 'desc')
                    ->take($this->qtdEvent)
                    ->get();
        return parent::responseGeneric($data);
    }

    public function store(Request $request): JsonResponse
    {
        return parent::store($request);
    }

    public function getNewEvents(int $id): JsonResponse
    {
        $data = $this->model->where('id', '>', $id)->get();
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
        $event = $this->model->find($id);
        return parent::response($event);
    }

    public function getAll(Request $request)
    {
        return $this->index($request);
    }
}

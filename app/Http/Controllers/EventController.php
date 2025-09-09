<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class EventController extends Controller
{
    private $model;
    private $newEvents;
    private $eventData = [];

    public function __construct(Event $event)
    {
        $this->model = $event;
    }

    public function index(Request $request): JsonResponse
    {
        $this->listNewEvents();
        if ($this->newEvents) {
            $this->readNewEvents();
            $this->deleteNewEventsFile();
            $this->saveEventData();
        }
        return response()->json($this->getLatestEvents(100), 200);
    }

    public function save($data): void
    {
        $this->model->create($data);
    }

    public function saveEventData(): void
    {
        foreach ($this->eventData as $data) {
            $this->save($data);
        }
    }

    public function listNewEvents(): void
    {
        $files = Storage::files('public');
        $this->newEvents = array_filter($files, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'json';
        });
    }

    public function readNewEvents(): void
    {
        foreach ($this->newEvents as $event) {
            $content = Storage::get($event);
            $arrayData = json_decode($content, true);
            foreach ($arrayData as $data) {
                $this->eventData[] = $data;
            }
        }
    }

    public function deleteNewEventsFile(): void
    {
        foreach ($this->newEvents as $event) {
            Storage::delete($event);
        }
    }

    public function getLatestEvents(int $qtd): Collection
    {
        return $this->model
                    ->whereDate('event_date_time', Carbon::today())
                    ->orderBy('event_date_time', 'desc')
                    ->take($qtd)
                    ->get();
    }
}

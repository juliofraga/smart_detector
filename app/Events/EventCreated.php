<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventData;

    public function __construct($eventModel)
    {
        $this->eventData = $eventModel->toArray();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('events');
    }

    public function broadcastAs()
    {
        return 'EventCreated';
    }
}

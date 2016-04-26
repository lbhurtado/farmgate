<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Entities\ShortMessage;

class ShortMessageWasRecorded extends Event
{
    use SerializesModels;

    public $shortMessage;

    public function __construct(ShortMessage $shortMessage)
    {
        $this->shortMessage = $shortMessage;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}

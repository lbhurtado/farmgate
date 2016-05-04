<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Events\Event;
use App\Instruction;

class PollResultsWereProcessed extends Event
{
    use SerializesModels;

    public $instruction;

    public $results;

    /**
     * PollResultsWereProcessed constructor.
     * @param Instruction $instruction
     * @param $results
     */
    public function __construct(Instruction $instruction, $results)
    {
        $this->instruction = $instruction;
        $this->reesults = $results;
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

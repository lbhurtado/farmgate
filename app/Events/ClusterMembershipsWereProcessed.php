<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ClusterMembershipsWereProcessed extends Event
{
    use SerializesModels;

    public $cluster;

    public function __construct($cluster)
    {
        $this->cluster = $cluster;
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

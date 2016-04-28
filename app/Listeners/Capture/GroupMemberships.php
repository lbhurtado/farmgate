<?php

namespace App\Listeners\Capture;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Events\ShortMessageWasRecorded;
use App\Repositories\TokenRepository;

class GroupMemberships
{
    use DispatchesJobs;

    private $tokens;

    public function __construct(TokenRepository $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Handle the event.
     *
     * @param  ShortMessageWasRecorded  $event
     * @return void
     */
    public function handle(ShortMessageWasRecorded $event)
    {
        //
    }
}

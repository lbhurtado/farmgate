<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Entities\ShortMessage;
use App\Jobs\TallyVotes;

class TallyVotesTest extends TestCase
{
    use DatabaseMigrationsWithSeeding, DispatchesJobs;

    /** @test */
    function short_message_with_proper_syntax_dispatches_tally_votes_job()
    {
        $this->expectsJobs(TallyVotes::class);

        factory(ShortMessage::class)->create([
            'message' => "TXT POLL MARCOS 777 ROBREDO 222",
        ]);
    }
}

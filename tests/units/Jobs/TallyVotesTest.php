<?php

use App\Repositories\ElectionResultRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\CandidateRepository;
use App\Repositories\TokenRepository;
use App\Entities\ShortMessage;
use App\Entities\Candidate;
use App\Entities\Cluster;
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

    /** @test */
    function tally_votes_does_the_job()
    {
        $election_results = $this->app->make(ElectionResultRepository::class)->skipPresenter();

        $cluster = factory(Cluster::class)->create();

        $claim_code = 'ABC1234';
        $tokens = $this->app->make(TokenRepository::class)->skipPresenter();

        $tokens->create([
            'code'       => $claim_code,
            'class'      => Cluster::class,
            'reference'  => $cluster->id
        ]);
        $message1 = factory(ShortMessage::class)->create(['message' => $claim_code]);

        $this->assertEquals($cluster->name, $message1->contact->cluster->name);
        $this->assertCount(0, $election_results->all());

        $message2 = factory(ShortMessage::class)->create([
            'from' => $message1->from,
            'message' => "txt poll marcos 777 ROBREDO 222 ESCUDERO 1"
        ]);

        $this->assertEquals($cluster->name, $message2->contact->cluster->name);
        $this->assertEquals("TXT POLL", $message2->getInstruction()->getKeyword());

        $this->app->make(CandidateRepository::class)->create([
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "MARCOS"
        ]);

        $this->app->make(CandidateRepository::class)->create([
            'name'  => "Leni Robredo",
            'alias' => "ROBREDO"
        ]);

        $job = new TallyVotes($message2->getInstruction());
        $this->dispatch($job);

        $this->assertCount(2, $election_results->all());

    }
}

<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ElectionResultRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Events\PollResultsWereProcessed;
use App\Repositories\TokenRepository;
use App\Criteria\CandidateCriterion;
use App\Entities\ShortMessage;
use App\Entities\Candidate;
use App\Entities\Cluster;
use App\Jobs\TallyVotes;
use App\Instruction;

class TallyVotesTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    private $election_results;

    private $tokens;

    private $poll_keyword;

    function setUp()
    {
        parent::setUp();

        $this->election_results = $this->app->make(ElectionResultRepository::class)->skipPresenter();
        $this->tokens = $this->app->make(TokenRepository::class)->skipPresenter();
        $this->poll_keyword = strtoupper(Instruction::$keywords['POLL']);
    }
    /** @test */
    function short_message_with_proper_syntax_dispatches_tally_votes_job()
    {
        $this->expectsJobs(TallyVotes::class);

        factory(ShortMessage::class)->create([
            'message' => $this->poll_keyword . " " . "MARCOS 777 ROBREDO 222",
        ]);
    }

    /** @test */
    function tally_votes_does_the_job()
    {
        $candidate1 = factory(Candidate::class)->create([
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "MARCOS"
        ]);

        $candidate2 = factory(Candidate::class)->create([
            'name'  => "Leni Robredo",
            'alias' => "ROBREDO"
        ]);

        $cluster1 = factory(Cluster::class)->create();
        $token_code1 = 'ABC1234';
        $this->tokens->create([
            'code'       => $token_code1,
            'class'      => Cluster::class,
            'reference'  => $cluster1->id
        ]);
        $message1 = factory(ShortMessage::class)->create([
            'from' => '09173011987',
            'message' => $token_code1
        ]);

        $this->assertCount(0, $this->election_results->all());

        $mobile1 = $message1->from;
        $message2 = factory(ShortMessage::class)->create([
            'from' => $mobile1,
            'message' => $this->poll_keyword . " " . "marcos 777 ROBREDO 222 ESCUDERO 1"
        ]);

        $this->assertEquals($this->poll_keyword, $message2->getInstruction()->getKeyword());

        $job = new TallyVotes($message2->getInstruction());
        $this->dispatch($job);

        $this->assertCount(2, $this->election_results->all());
        $this->assertEquals(777, $this->election_results->getByCriteria(new CandidateCriterion($candidate1))->sum('votes'));
        $this->assertEquals(222, $this->election_results->getByCriteria(new CandidateCriterion($candidate2))->sum('votes'));

        $message3 = factory(ShortMessage::class)->create([
            'from' => $mobile1,
            'message' => $this->poll_keyword . " " . "marcos 778 ROBREDO 2230 ESCUDERO 1"
        ]);
        $this->assertCount(2, $this->election_results->all());
        $this->assertEquals(778, $this->election_results->getByCriteria(new CandidateCriterion($candidate1))->sum('votes'));
        $this->assertEquals(222, $this->election_results->getByCriteria(new CandidateCriterion($candidate2))->sum('votes'));
    }

    /** @test */
    function tally_votes_fires_event()
    {
        $candidate1 = factory(Candidate::class)->create([
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "MARCOS"
        ]);

        $candidate2 = factory(Candidate::class)->create([
            'name'  => "Leni Robredo",
            'alias' => "ROBREDO"
        ]);

        $cluster1 = factory(Cluster::class)->create();
        $token_code1 = 'ABC1234';
        $this->tokens->create([
            'code'       => $token_code1,
            'class'      => Cluster::class,
            'reference'  => $cluster1->id
        ]);
        $message1 = factory(ShortMessage::class)->create([
            'from' => '09189362340',
            'message' => $token_code1
        ]);

        $this->assertCount(0, $this->election_results->all());

        $mobile1 = $message1->from;
        $message2 = factory(ShortMessage::class)->create([
            'from' => $mobile1,
            'message' => $this->poll_keyword . " " . "marcos 777 ROBREDO 111 ESCUDERO 1"
        ]);

        $this->assertEquals($this->poll_keyword, $message2->getInstruction()->getKeyword());

        $this->expectsEvents(PollResultsWereProcessed::class);

        $job = new TallyVotes($message2->getInstruction());
        $this->dispatch($job);

    }
}

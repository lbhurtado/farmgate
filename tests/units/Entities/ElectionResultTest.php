<?php

use App\Repositories\ElectionResultRepository;
use App\Repositories\CandidateRepository;
use App\Entities\ElectionResult;
use App\Entities\Candidate;
use App\Entities\Cluster;

class ElectionResultTest extends TestCase
{
    use DatabaseMigrationsWithSeeding;

    /** @test */
    function election_results_has_a_votes_field()
    {
        $election_result = $this->app->make(ElectionResultRepository::class)->skipPresenter()->create(['votes' => 100]);
        $this->assertInstanceOf(ElectionResult::class, $election_result);
        $this->assertEquals(100, $election_result->votes);
        $this->seeInDatabase($election_result->getTable(), [
            'votes'  => 100,
        ]);
    }

    /** @test */
    function election_results_votes_field_has_a_maximum_value()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(ElectionResultRepository::class)->create([
            'votes' => 1002
        ]);
    }

    /** @test */
    function election_results_has_candidate_and_cluster()
    {
        $candidate = factory(Candidate::class)->create();
        $cluster = factory(Cluster::class)->create();
        $election_result =
            $this->app->make(ElectionResultRepository::class)->skipPresenter()
                ->create(['votes' => 100]);
        $election_result->candidate()->associate($candidate);
        $election_result->cluster()->associate($cluster);
        $election_result->save();

        $this->assertEquals($candidate->name, $election_result->candidate->name);
        $this->assertEquals($cluster->name, $election_result->cluster->name);
        $this->seeInDatabase($election_result->getTable(), [
            'candidate_id' => $candidate->id,
            'cluster_id' => $cluster->id,
            'votes' => 100,
        ]);
    }

    /** @test */
    function election_results_can_be_in_one_shot()
    {
        $candidate = factory(Candidate::class)->create();
        $cluster = factory(Cluster::class)->create();
        $election_result =
            $this->app->make(ElectionResultRepository::class)->skipPresenter()
                ->createElectionResult(['votes' => 100], $candidate, $cluster);

        $this->assertEquals($candidate->name, $election_result->candidate->name);
        $this->assertEquals($cluster->name, $election_result->cluster->name);
        $this->seeInDatabase($election_result->getTable(), [
            'candidate_id' => $candidate->id,
            'cluster_id' => $cluster->id,
            'votes' => 100,
        ]);
    }

    /** @test */
    function election_results_has_presenter()
    {
        $candidate = $this->app->make(CandidateRepository::class)->skipPresenter()->create([
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "MARCOS"
        ]);
        $cluster = factory(Cluster::class)->create();
        $this->app->make(ElectionResultRepository::class)->skipPresenter()->createElectionResult(['votes' => 100], $candidate, $cluster);

        $election_results = App::make(ElectionResultRepository::class);
        $arr = $election_results->find(1);

        $this->assertEquals(100, $arr['data']['votes']);
        $this->assertEquals("Ferndinand Marcos Jr.", $arr['data']['candidate']['name']);
        $this->assertEquals("MARCOS", $arr['data']['candidate']['alias']);
        $this->assertEquals($cluster->name, $arr['data']['cluster']['name']);
    }
}
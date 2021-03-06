<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ElectionResultRepository;
use App\Repositories\CandidateRepository;
use App\Criteria\CandidateCriterion;
use App\Entities\ElectionResult;
use App\Criteria\TownCriterion;
use App\Entities\Candidate;
use App\Entities\Cluster;
use App\Entities\Town;

class ElectionResultTest extends TestCase
{
    use DatabaseMigrations;

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
    function election_results_votes_field_has_a_maximum_value_upon_creation()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(ElectionResultRepository::class)->create([
            'votes' => 1002
        ]);
    }

    /** @test */
    function election_results_votes_field_has_a_maximum_value_upon_update()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $election_results = $this->app->make(ElectionResultRepository::class)->skipPresenter();
        $election_result = $election_results->create([
            'votes' => 100
        ]);

        $election_results->update(['votes' => 1002], $election_result->id);
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
                ->createElectionResult(100, $candidate, $cluster);

        $this->assertEquals($candidate->name, $election_result->candidate->name);
        $this->assertEquals($cluster->name, $election_result->cluster->name);
        $this->seeInDatabase($election_result->getTable(), [
            'candidate_id' => $candidate->id,
            'cluster_id' => $cluster->id,
            'votes' => 100,
        ]);
    }

    /** @test */
    function election_result_has_presenter()
    {
        $candidate = $this->app->make(CandidateRepository::class)->skipPresenter()->create([
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "MARCOS"
        ]);
        $cluster = factory(Cluster::class)->create();
        $result = $this->app->make(ElectionResultRepository::class)->skipPresenter()->createElectionResult(100, $candidate, $cluster);

        $election_results = App::make(ElectionResultRepository::class);
        $arr = $election_results->find(1);

        $this->assertEquals($result->votes, $arr['data']['votes']);
        $this->assertEquals($candidate->name, $arr['data']['candidate']['name']);
        $this->assertEquals($candidate->alias, $arr['data']['candidate']['alias']);
        $this->assertEquals($cluster->name, $arr['data']['cluster']['name']);
    }

    /** @test */
    function election_results_has_a_factory()
    {
        factory(ElectionResult::class,10)->create(['votes' => 500]);
        $election_results = $this->app->make(ElectionResultRepository::class)->skipPresenter();

        $this->assertCount(10, $election_results->all());
        $this->assertEquals(5000, $election_results->all()->sum('votes'));
    }

    /** @test */
    function election_results_is_unique_per_candidate_per_cluster()
    {
        $candidate = $this->app->make(CandidateRepository::class)->skipPresenter()->create([
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "MARCOS"
        ]);
        $cluster = factory(Cluster::class)->create();
        $election_result = $this->app->make(ElectionResultRepository::class)->skipPresenter()->createElectionResult(100, $candidate, $cluster);

        $this->assertCount(1, $election_result->all());

        $this->app->make(ElectionResultRepository::class)->skipPresenter()->createElectionResult(102, $candidate, $cluster);
        $this->assertCount(1, $election_result->all());
    }

    /** @test */
    function election_results_can_be_summed_per_criterion()
    {
        $candidate1 = $this->app->make(CandidateRepository::class)->skipPresenter()->create([
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "MARCOS"
        ]);
        $candidate2 = $this->app->make(CandidateRepository::class)->skipPresenter()->create([
            'name'  => "Leni Robredo",
            'alias' => "ROBREDO"
        ]);
        $town1 = factory(Town::class)->create();
        $town2 = factory(Town::class)->create();
        $cluster1 = factory(Cluster::class)->create(['town_id' => $town1->id]);
        $cluster2 = factory(Cluster::class)->create(['town_id' => $town1->id]);
        $cluster3 = factory(Cluster::class)->create(['town_id' => $town2->id]);
        $cluster4 = factory(Cluster::class)->create(['town_id' => $town2->id]);
        $cluster5 = factory(Cluster::class)->create(['town_id' => $town2->id]);
        $election_results = $this->app->make(ElectionResultRepository::class)->skipPresenter();
        $election_results->createElectionResult(100, $candidate1, $cluster1);
        $election_results->createElectionResult(200, $candidate1, $cluster2);
        $election_results->createElectionResult(300, $candidate1, $cluster3);
        $election_results->createElectionResult(400, $candidate2, $cluster4);
        $election_results->createElectionResult(500, $candidate2, $cluster5);

        $this->assertCount(5, $election_results->all());
        $this->assertEquals(600, $election_results->getByCriteria(new CandidateCriterion($candidate1))->sum('votes'));
        $this->assertEquals(900, $election_results->getByCriteria(new CandidateCriterion($candidate2))->sum('votes'));
        $this->assertEquals(1200, $election_results->getByCriteria(new TownCriterion($town2))->sum('votes'));
        $this->assertEquals(1500, $election_results->all()->sum('votes'));
    }
}

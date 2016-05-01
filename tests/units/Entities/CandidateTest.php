<?php

use App\Repositories\CandidateRepository;
use App\Entities\Candidate;

class CandidateTest extends TestCase
{
    use DatabaseMigrationsWithSeeding;

    /** @test */
    function candidate_has_a_name_and_alias()
    {
        $candidate = $this->app->make(CandidateRepository::class)->skipPresenter()->create([
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "MARCOS"
        ]);

        $this->assertInstanceOf(Candidate::class, $candidate);
        $this->assertEquals('Ferndinand Marcos Jr.', $candidate->name);
        $this->assertEquals('MARCOS', $candidate->alias);
        $this->seeInDatabase($candidate->getTable(), [
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "MARCOS"
        ]);
    }

    /** @test */
    function candidate_has_a_presenter()
    {
        $candidate = $this->app->make(CandidateRepository::class)->create([
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "MARCOS"
        ]);

        $this->assertEquals("Ferndinand Marcos Jr.", $candidate['data']['name']);
        $this->assertEquals("MARCOS", $candidate['data']['alias']);
    }

    /** @test */
    function candidate_can_be_looked_up_using_alias()
    {
        $this->app->make(CandidateRepository::class)->create([
            'name'  => "Ferndinand Marcos Jr.",
            'alias' => "marcos"
        ]);

        $this->app->make(CandidateRepository::class)->create([
            'name'  => "Leni Robredo",
            'alias' => "ROBREDO"
        ]);

        $candidates = $this->app->make(CandidateRepository::class)->skipPresenter();

        $this->assertEquals("Ferndinand Marcos Jr.", $candidates->findByAlias('marcos')->name);
    }
}

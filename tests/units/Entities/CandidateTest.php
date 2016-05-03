<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ElectivePositionRepository;
use App\Repositories\CandidateRepository;
use App\Entities\Candidate;

class CandidateTest extends TestCase
{
    use DatabaseMigrations;

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

    /** @test */
    function candidate_has_an_elective_position()
    {
        $this->artisan('db:seed');

        $candidate = $this->app->make(CandidateRepository::class)->skipPresenter()->findByField('alias', "MARCOS")->first();

        $elective_position = $this->app->make(ElectivePositionRepository::class)->skipPresenter()->findByField('name', 'Vice-President')->first();
        $candidate->elective_position()->associate($elective_position)->save();

        $this->assertEquals('Bong-bong Marcos', $candidate->name);
        $this->assertEquals('Vice-President', $candidate->elective_position->name);
    }

    /** @test */
    function seed_candidates()
    {
        $this->artisan('db:seed');

        $this->seeInDatabase('candidates', ['name' => "Rody Duterte",    'alias' => "DUTERTE"]);
        $this->seeInDatabase('candidates', ['name' => "Jojo Binay",      'alias' => "BINAY"]);
        $this->seeInDatabase('candidates', ['name' => "Grace Poe",       'alias' => "POE"]);
        $this->seeInDatabase('candidates', ['name' => "Mar Roxas",       'alias' => "ROXAS"]);
        $this->seeInDatabase('candidates', ['name' => "Miriam Santiago", 'alias' => "SANTIAGO"]);
    }
}

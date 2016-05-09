<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ElectivePositionRepository;
use App\Repositories\TownRepository;
use App\Entities\ElectivePosition;

class ElectivePositionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function elective_position_has_a_name_and_tag()
    {
        $elective_position = $this->app->make(ElectivePositionRepository::class)->skipPresenter()->create([
            'name' => "Councilor of Malabon",
            'tag' => 8
        ]);

        $this->assertInstanceOf(ElectivePosition::class, $elective_position);
        $this->assertEquals('Councilor of Malabon', $elective_position->name);
        $this->assertEquals(8 , $elective_position->tag);
        $this->assertEquals('Councilor of Malabon', $elective_position->name);
        $this->seeInDatabase($elective_position->getTable(), [
            'name' => "Councilor of Malabon",
            'tag' => 8
        ]);
    }

    /** @test */
    function seed_elective_positions()
    {
        $this->artisan('db:seed');
        $this->seeInDatabase('elective_positions', ['name' => "President",                    'tag' => 1]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-President',               'tag' => 2]);
        $this->seeInDatabase('elective_positions', ['name' => 'Mayor of CAVITE CITY',         'tag' => 6]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-Mayor of CAVITE CITY',    'tag' => 7]);
        $this->seeInDatabase('elective_positions', ['name' => 'Mayor of AMADEO',              'tag' => 6]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-Mayor of AMADEO',         'tag' => 7]);
        $this->seeInDatabase('elective_positions', ['name' => 'Mayor of BACOOR',              'tag' => 6]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-Mayor of BACOOR',         'tag' => 7]);
        $this->seeInDatabase('elective_positions', ['name' => 'Mayor of CARMONA',             'tag' => 6]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-Mayor of CARMONA',        'tag' => 7]);
    }
}

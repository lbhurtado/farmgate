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
        $this->seeInDatabase('elective_positions', ['name' => 'Mayor of Alfonso',             'tag' => 6]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-Mayor of Alfonso',        'tag' => 7]);
        $this->seeInDatabase('elective_positions', ['name' => 'Mayor of Amadeo',              'tag' => 6]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-Mayor of Amadeo',         'tag' => 7]);
        $this->seeInDatabase('elective_positions', ['name' => 'Mayor of Bacoor',              'tag' => 6]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-Mayor of Bacoor',         'tag' => 7]);
        $this->seeInDatabase('elective_positions', ['name' => 'Mayor of Carmona',             'tag' => 6]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-Mayor of Carmona',        'tag' => 7]);
    }
}

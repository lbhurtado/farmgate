<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ElectivePositionRepository;
use App\Entities\ElectivePosition;

class ElectivePositionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function elective_position_has_a_name_and_tag()
    {
        $elective_position = $this->app->make(ElectivePositionRepository::class)->skipPresenter()->create([
            'name' => "President",
            'tag' => 1
        ]);

        $this->assertInstanceOf(ElectivePosition::class, $elective_position);
        $this->assertEquals('President', $elective_position->name);
        $this->seeInDatabase($elective_position->getTable(), [
            'name' => "President",
            'tag' => 1
        ]);
    }

    /** @test */
    function seed_elective_positions()
    {
        $this->artisan('db:seed');
        $this->seeInDatabase('elective_positions', ['name' => "President", 'tag' => 1]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-President',    'tag' => 2]);
        $this->seeInDatabase('elective_positions', ['name' => 'Governor',          'tag' => 3]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-Governor',     'tag' => 4]);
        $this->seeInDatabase('elective_positions', ['name' => 'Congressman',       'tag' => 5]);
        $this->seeInDatabase('elective_positions', ['name' => 'Mayor',             'tag' => 6]);
        $this->seeInDatabase('elective_positions', ['name' => 'Vice-Mayor',        'tag' => 7]);
    }
}

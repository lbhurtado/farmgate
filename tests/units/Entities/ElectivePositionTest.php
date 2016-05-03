<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ElectivePositionRepository;
use App\Entities\ElectivePosition;

class ElectivePositionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function elective_position_has_a_name()
    {
        $elective_position = $this->app->make(ElectivePositionRepository::class)->skipPresenter()->create([
            'name' => "President"
        ]);

        $this->assertInstanceOf(ElectivePosition::class, $elective_position);
        $this->assertEquals('President', $elective_position->name);
        $this->seeInDatabase($elective_position->getTable(), [
            'name' => "President"
        ]);
    }
}

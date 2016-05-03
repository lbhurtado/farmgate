<?php

use App\Repositories\PollingPlaceRepository;
use App\Entities\PollingPlace;
use App\Entities\Barangay;

class PollingPlaceTest extends TestCase
{
    use DatabaseMigrationsWithSeeding;

    /** @test */
    function polling_place_has_a_name()
    {
        $polling_place = $this->app->make(PollingPlaceRepository::class)->skipPresenter()->create([
            'name' => "Elementary School"
        ]);

        $this->assertInstanceOf(PollingPlace::class, $polling_place);
        $this->assertEquals('Elementary School', $polling_place->name);
        $this->seeInDatabase($polling_place->getTable(), [
            'name' => "Elementary School"
        ]);
    }

    /** @test */
    function polling_place_name_field_is_required()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(PollingPlaceRepository::class)->create([]);
    }

    /** @test */
    function polling_place_name_field_is_not_blank()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(PollingPlaceRepository::class)->create([
            'name'  => '',
        ]);
    }

    /** @test */
    function polling_place_factory_is_valid()
    {
        $polling_place = factory(PollingPlace::class)->create();
        $validator = Validator::make(
            $polling_place->all()->toArray(),
            [
                'name' => array('required,min:2'),
            ]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    function polling_place_has_a_presenter()
    {
        $contact = $this->app->make(PollingPlaceRepository::class)->create([
            'name' => 'Elementary School'
        ]);

        $this->assertEquals(
            'Elementary School',
            $contact['data']['name']
        );
    }

    /** @test */
    function polling_place_is_part_of_a_barangay()
    {
        $barangay = factory(Barangay::class)->create();
        $polling_place = factory(PollingPlace::class)->create();

        $polling_place->barangay()->associate($barangay);
        $polling_place->save();

        $this->assertEquals($barangay->name, $polling_place->barangay->find($barangay->id)->name);
        $this->seeInDatabase($polling_place->getTable(),[
            'name' => $polling_place->name,
            'barangay_id' => $barangay->id
        ]);
    }

    /** @test */
    function polling_has_unique_name_field_in_every_barangay()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        $barangay = factory(Barangay::class)->create();
        $polling_place1 = factory(PollingPlace::class)->create(['name' => 'Elementary School']);
        $polling_place1->barangay()->associate($barangay);
        $polling_place1->save();
        $polling_place2 = factory(PollingPlace::class)->create(['name' => 'Elementary School']);
        $polling_place2->barangay()->associate($barangay);
        $polling_place2->save();
    }
}

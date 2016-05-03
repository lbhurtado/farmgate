<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\BarangayRepository;
use App\Entities\Barangay;
use App\Entities\Town;

class BarangayTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function barangay_has_name()
    {
        $barangay = $this->app->make(BarangayRepository::class)->skipPresenter()->create([
            'name' => "Poblacion"
        ]);

        $this->assertInstanceOf(Barangay::class, $barangay);
        $this->assertEquals('Poblacion', $barangay->name);
        $this->seeInDatabase($barangay->getTable(), [
            'name' => "Poblacion"
        ]);
    }

    /** @test */
    function barangay_name_field_is_required()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(BarangayRepository::class)->create([]);
    }

    /** @test */
    function barangay_name_field_is_not_blank()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(BarangayRepository::class)->create([
            'name'  => '',
        ]);
    }

    /** @test */
    function barangay_factory_is_valid(){
        $group = factory(Barangay::class)->create();
        $validator = Validator::make(
            $group->all()->toArray(),
            [
                'name' => array('required,min:2'),
            ]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    function barangay_has_a_presenter()
    {
        $contact = $this->app->make(BarangayRepository::class)->create([
            'name' => 'Poblacion'
        ]);

        $this->assertEquals(
            'Poblacion',
            $contact['data']['name']
        );
    }

    /** @test */
    function barangay_is_part_of_a_town()
    {
        $town = factory(Town::class)->create();
        $barangay = factory(Barangay::class)->create();

        $barangay->town()->associate($town)->save();

        $this->assertEquals($town->name, $barangay->town->find($town->id)->name);
        $this->seeInDatabase($barangay->getTable(),[
            'name' => $barangay->name,
            'town_id' => $town->id
        ]);
    }

    /** @test */
    function barangay_has_unique_name_field_in_every_town()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        $town = factory(Town::class)->create();
        $barangay1 = factory(Barangay::class)->create(['name' => 'Poblacion 1']);
        $barangay1->town()->associate($town);
        $barangay1->save();
        $barangay2 = factory(Barangay::class)->create(['name' => 'Poblacion 1']);
        $barangay2->town()->associate($town);
        $barangay2->save();
    }
}

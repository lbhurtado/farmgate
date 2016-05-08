<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\DistrictRepository;
use App\Entities\District;

class DistrictTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function district_has_a_name()
    {
        $district = $this->app->make(DistrictRepository::class)->skipPresenter()->create([
            'name' => "1st District"
        ]);

        $this->assertInstanceOf(District::class, $district);
        $this->assertEquals('1st District', $district->name);
        $this->seeInDatabase($district->getTable(), [
            'name' => "1st District"
        ]);
    }

    /** @test */
    function district_name_field_is_required()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(DistrictRepository::class)->create([]);
    }

    /** @test */
    function district_name_field_is_not_blank()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(DistrictRepository::class)->create([
            'name'  => '',
        ]);
    }

    /** @test */
    function district_factory_is_valid(){
        $district = factory(District::class)->create();
        $validator = Validator::make(
            $district->all()->toArray(),
            [
                'name' => array('required,min:2'),
            ]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    function district_has_a_presenter()
    {
        $contact = $this->app->make(DistrictRepository::class)->create([
            'name' => '1st District'
        ]);

        $this->assertEquals(
            '1st District',
            $contact['data']['name']
        );
    }
}

<?php

use App\Repositories\ClusterRepository;
use App\Entities\Cluster;
use App\Entities\Town;

class ClusterTest extends TestCase
{
    use DatabaseMigrationsWithSeeding;

    /** @test */
    function cluster_has_name_precincts_and_registered_voters()
    {
        $cluster = App::make(ClusterRepository::class)->skipPresenter()->create([
            'name' => "Cluster 1",
            'precincts' => '1A, 2A, 3A, 4K',
            'registered_voters' => 800
        ]);

        $this->assertInstanceOf(Cluster::class, $cluster);
        $this->assertEquals('Cluster 1', $cluster->name);
        $this->seeInDatabase($cluster->getTable(), [
            'name' => "Cluster 1"
        ]);
    }

    /** @test */
    function cluster_name_field_is_required()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        App::make(ClusterRepository::class)->create([
            'precincts' => '1A, 2A, 3A, 4K',
            'registered_voters' => 800
        ]);
    }

    /** @test */
    function cluster_name_field_is_not_blank()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        App::make(ClusterRepository::class)->create([
            'name' => "",
            'precincts' => '1A, 2A, 3A, 4K',
            'registered_voters' => 800
        ]);
    }

    /** @test */
    function cluster_precincts_field_is_required()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        App::make(ClusterRepository::class)->create([
            'name' => "Cluster 1",
            'registered_voters' => 800
        ]);
    }

    /** @test */
    function cluster_precincts_field_is_not_blank()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        App::make(ClusterRepository::class)->create([
            'name' => "Cluster 1",
            'precincts' => '',
            'registered_voters' => 800
        ]);
    }

    /** @test */
    function cluster_registered_voters_field_is_required()
    {
        //daya lang
        //created default attribute to zero in model class
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        App::make(ClusterRepository::class)->create([
            'name' => "Cluster 1",
            'precincts' => '1A, 2A, 3A, 4K',
        ]);
    }

    /** @test */
    function cluster_registered_voters_field_has_a_maximum_value()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        App::make(ClusterRepository::class)->create([
            'name' => "Cluster 1",
            'precincts' => '1A, 2A, 3A, 4K',
            'registered_voters' => 8000
        ]);
    }

    /** @test */
    function cluster_factory_is_valid(){
        $cluster = factory(Cluster::class)->create();
        $validator = Validator::make(
            $cluster->all()->toArray(),
            [
                'name'	=> 'required,',
                'precincts'	=> 'required,',
                'registered_voters' => 'required,integer|min:100|max:1000,'
            ]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    function cluster_has_a_presenter()
    {
        $cluster = App::make(ClusterRepository::class)->create([
            'name' => "Cluster 1",
            'precincts' => '1A, 2A, 3A, 4K',
            'registered_voters' => 800
        ]);

        $this->assertEquals(
            [
                'name' => "Cluster 1",
                'precincts' => '1A, 2A, 3A, 4K',
                'registered_voters' => 800
            ],
            array_only($cluster['data'], ['name', 'precincts', 'registered_voters'])
        );
    }

    /** @test */
    function cluster_has_unique_name_field()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        App::make(ClusterRepository::class)->create([
            'name' => "Cluster 1",
            'precincts' => '1A, 2A, 3A, 4K',
            'registered_voters' => 800
        ]);

        App::make(ClusterRepository::class)->create([
            'name' => "Cluster 1",
            'precincts' => '1A, 2A, 3A, 4K',
            'registered_voters' => 800
        ]);
    }

    /** @test */
    function cluster_is_in_a_town()
    {
        $town = factory(Town::class)->create();
        $cluster = factory(Cluster::class)->create();

        $cluster->town()->associate($town);
        $cluster->save();

        $this->assertEquals($town->name, $cluster->town->find($town->id)->name);
        $this->seeInDatabase($cluster->getTable(),[
            'name' => $cluster->name,
            'town_id' => $town->id
        ]);
    }
}

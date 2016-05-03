<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\TownRepository;
use App\Entities\Cluster;
use App\Entities\Town;

class TownTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function town_has_name()
    {
        $town = $this->app->make(TownRepository::class)->skipPresenter()->create([
            'name' => "Currimao"
        ]);

        $this->assertInstanceOf(Town::class, $town);
        $this->assertEquals('Currimao', $town->name);
        $this->seeInDatabase($town->getTable(), [
            'name' => "Currimao"
        ]);
    }

    /** @test */
    function town_name_field_is_required()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(TownRepository::class)->create([]);
    }

    /** @test */
    function town_name_field_is_not_blank()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(TownRepository::class)->create([
            'name'  => '',
        ]);
    }

    /** @test */
    function town_factory_is_valid(){
        $group = factory(Town::class)->create();
        $validator = Validator::make(
            $group->all()->toArray(),
            [
                'name' => array('required,min:2'),
            ]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    function town_has_a_presenter()
    {
        $contact = $this->app->make(TownRepository::class)->create([
            'name' => 'Currimao'
        ]);

        $this->assertEquals(
            'Currimao',
            $contact['data']['name']
        );
    }

    /** @test */
    function town_has_unique_name_field()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        $this->app->make(TownRepository::class)->create([
            'name' => 'Currimao'
        ]);

        $this->app->make(TownRepository::class)->create([
            'name' => 'Currimao'
        ]);
    }

    /** @test */
    function town_has_many_clusters()
    {
        $town = factory(Town::class)->create(['name' => 'Currimao']);
        $cluster1 = factory(Cluster::class)->create();
        $cluster2 = factory(Cluster::class)->create();
        $town->clusters()->save($cluster1);
        $town->clusters()->save($cluster2);
        $town->save();

        $this->assertCount(2, $town->clusters);
        $this->assertEquals($cluster1->name, $town->clusters->find($cluster1->id)->name);
        $this->assertEquals($cluster2->name, $town->clusters->find($cluster2->id)->name);
        $this->seeInDatabase($cluster1->getTable(),[
            'name' => $cluster1->name,
            'town_id' => $town->id
        ]);
    }
}

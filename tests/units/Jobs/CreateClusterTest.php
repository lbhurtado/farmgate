<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\PollingPlaceRepository;
use App\Repositories\BarangayRepository;
use App\Repositories\ClusterRepository;
use App\Repositories\TownRepository;
use App\Jobs\CreateCluster;
use App\Entities\Cluster;

class CreateClusterTest extends TestCase
{
    use DatabaseMigrationsWithSeeding, DispatchesJobs;

    /** @test */
    function create_cluster_does_the_job()
    {
        $town = 'CARMONA';
        $barangay = 'BARANGAY I (POB.)';
        $polling_place = 'CARMONA NATIONAL HIGH SCHOOL, PURIFICACION ST. BRGY. 8 ';
        $precincts = '1A, 2A, 2B, 3A, 3B';
        $cluster = 1;
        $registered_voters = 612;

        $job = new CreateCluster($town, $barangay, $polling_place, $precincts, $cluster, $registered_voters);
        $this->dispatch($job);
        $clusters = $this->app->make(ClusterRepository::class)->skipPresenter();

        $this->assertCount(1, $clusters->all());

        $cluster_instance = $clusters->find(1);

        $this->assertInstanceOf(Cluster::class, $cluster_instance);
        $this->assertEquals($cluster, $cluster_instance->name);
        $this->assertEquals($precincts, $cluster_instance->precincts);
        $this->assertEquals($registered_voters, $cluster_instance->registered_voters);
        $this->assertEquals($polling_place, $cluster_instance->polling_place->name);
        $this->assertEquals($barangay, $cluster_instance->polling_place->barangay->name);
        $this->assertEquals($town, $cluster_instance->town->name);
        $this->assertEquals($town, $cluster_instance->polling_place->barangay->town->name);
        $this->seeInDatabase($cluster_instance->getTable(), [
            'name' => $cluster,
            'precincts' => $precincts,
            'registered_voters' => $registered_voters,
            'polling_place_id' => $cluster_instance->polling_place->id,
            'town_id' => $cluster_instance->town->id,
        ]);
    }

    /** @test */
    function create_cluster_duplication_will_update()
    {
        $clusters = $this->app->make(ClusterRepository::class)->skipPresenter();
        $towns = $this->app->make(TownRepository::class)->skipPresenter();
        $barangays = $this->app->make(BarangayRepository::class)->skipPresenter();
        $polling_places = $this->app->make(PollingPlaceRepository::class)->skipPresenter();

        $town1 = 'CARMONA';
        $barangay1 = 'BARANGAY I (POB.)';
        $polling_place1 = 'CARMONA NATIONAL HIGH SCHOOL, PURIFICACION ST. BRGY. 8 ';
        $precincts1 = '1A, 2A, 2B, 3A, 3B';
        $cluster1 = 1;
        $registered_voters1 = 612;

        $job1 = new CreateCluster($town1, $barangay1, $polling_place1, $precincts1, $cluster1, $registered_voters1);
        $this->dispatch($job1);

        $job1 = new CreateCluster($town1, $barangay1, $polling_place1, $precincts1, $cluster1, $registered_voters1);
        $this->dispatch($job1);

        $this->assertCount(1, $clusters->all());
        $this->assertCount(1, $towns->all());
        $this->assertCount(1, $barangays->all());
        $this->assertCount(1, $polling_places->all());
        $this->assertEquals($registered_voters1, $clusters->all()->sum('registered_voters'));

        $town2 = $town1;
        $barangay2 = $barangay1;
        $polling_place2 = $polling_place1;
        $precincts2 = '4A, 4B, 5A, 5B';
        $cluster2 = 2;
        $registered_voters2 = 637;

        $job2 = new CreateCluster($town2, $barangay2, $polling_place2, $precincts2, $cluster2, $registered_voters2);
        $this->dispatch($job2);

        $this->assertCount(2, $clusters->all());
        $this->assertCount(1, $towns->all());
        $this->assertCount(1, $barangays->all());
        $this->assertCount(1, $polling_places->all());
        $this->assertEquals($registered_voters1 + $registered_voters2, $clusters->all()->sum('registered_voters'));

        $town3 = $town1;
        $barangay3 = 'BARANGAY III (POB.)';
        $polling_place3 = 'CARMONA ELEMENTARY SCHOOL, PURIFICACION ST. BRGY. 8';
        $precincts3 = '11A, 12A, 12B, 13A, 13B';
        $cluster3 = 5;
        $registered_voters3 = 758;

        $job3 = new CreateCluster($town3, $barangay3, $polling_place3, $precincts3, $cluster3, $registered_voters3);
        $this->dispatch($job3);

        $this->assertCount(3, $clusters->all());
        $this->assertCount(1, $towns->all());
        $this->assertCount(2, $barangays->all());
        $this->assertCount(2, $polling_places->all());
        $this->assertEquals($registered_voters1 + $registered_voters2 + $registered_voters3, $clusters->all()->sum('registered_voters'));

        $town4 = 'CAVITE CITY';
        $barangay4 = 'BARANGAY 62-A (KANGKONG A)';
        $polling_place4 = 'PORTA VAGA ELEMENTARY SCHOOL, JUDGE IBAÑEZ ST.';
        $precincts4 = '1A, 1B, 1C, 2A, 2B';
        $cluster4 = 1;
        $registered_voters4 = 699;

        $job4 = new CreateCluster($town4, $barangay4, $polling_place4, $precincts4, $cluster4, $registered_voters4);
        $this->dispatch($job4);

        $this->assertCount(4, $clusters->all());
        $this->assertCount(2, $towns->all());
        $this->assertCount(3, $barangays->all());
        $this->assertCount(3, $polling_places->all());
        $this->assertEquals($registered_voters1 + $registered_voters2 + $registered_voters3 + $registered_voters4, $clusters->all()->sum('registered_voters'));

    }
}

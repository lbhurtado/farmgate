<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ClusterRepository;
use App\Repositories\TownRepository;
use App\Repositories\BarangayRepository;
use App\Jobs\CreateClustersFromCSV;

class CreateClustersFromCSVTest extends TestCase
{
    use DatabaseMigrationsWithSeeding, DispatchesJobs;

    /** @test */
    function create_clusters_from_csv_does_the_job()
    {
        $path = storage_path('app/public/pop.csv');

        $job = new CreateClustersFromCSV($path);
        $this->dispatch($job);

        $clusters = $this->app->make(ClusterRepository::class);

        $towns = $this->app->make(TownRepository::class);

        var_dump(array_pluck($towns->all()['data'], 'name'));

        $barangays = $this->app->make(BarangayRepository::class);

        var_dump(array_pluck($barangays->all()['data'], 'name'));
    }
}

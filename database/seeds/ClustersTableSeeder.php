<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\CreateClustersFromCSV;
use Illuminate\Database\Seeder;

class ClustersTableSeeder extends Seeder
{
    use DispatchesJobs;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path('app/public/pop.csv');

        $job = new CreateClustersFromCSV($path);
        $this->dispatch($job);
    }
}

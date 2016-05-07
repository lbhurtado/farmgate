<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ClusterRepository;
use Illuminate\Database\Seeder;
use App\Jobs\CreateCluster;
use League\Csv\Reader;

class ClustersTableSeeder extends Seeder
{
    use DispatchesJobs;

    private $reader;

    private $clusters;

    /**
     * @param ClusterRepository $clusters
     */
    public function __construct(ClusterRepository $clusters)
    {
        $this->reader = Reader::createFromPath(database_path('pops.csv'));
        $this->clusters = $clusters->skipPresenter();
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clusters')->delete();

        foreach ($this->reader as $index => $row) {
            if ($row[0] && $row[1] && $row[2]  && $row[3] && $row[4] && $row[5])
            {
                $town = $row[0];
                $barangay = $row[2];
                $polling_place = $row[3];
                $precincts =  $row[4];
                $cluster = $row[1];
                $registered_voters = $row[5];

                $job = new CreateCluster($town, $barangay, $polling_place, $precincts, $cluster, $registered_voters);
                $this->dispatch($job);
            }
//            if (\App::environment() == 'testing')
//                if ($index == 101-1) break;
        }
    }
}

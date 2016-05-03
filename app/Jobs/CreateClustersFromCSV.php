<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;

class CreateClustersFromCSV extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    private $reader;

    public function __construct($path)
    {
        $this->reader = Reader::createFromPath($path);
        $this->reader->setDelimiter(',');
        $this->reader->setEnclosure('"');
        $this->reader->setEscape('\\');
        $this->reader->setEncodingFrom('iso-8859-15');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "\n";
        foreach ($this->reader as $index => $row) {
            if ($row[0] && $row[1] && $row[2]  && $row[3] && $row[4] && $row[5])
            {
                $town = $row[0];
                $barangay = $row[2];
                $polling_place = $row[3];
                $precincts =  $row[4];
                $cluster = $row[1];
                $registered_voters = $row[5];

//                if ($town == 'BACOOR')
//                {
                    try
                    {
                        $job = new CreateCluster($town, $barangay, $polling_place, $precincts, $cluster, $registered_voters);
                        $this->dispatch($job);
                    }
                    catch (\Exception $e)
                    {
//                        echo $index . " " .  implode(',', $row) . " " . "\n";
                    }
//                }

            }
        }
    }
}

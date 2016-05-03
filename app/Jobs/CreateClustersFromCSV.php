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

    private $records;

    public function __construct($path, $records = 100000)
    {
        $this->reader = Reader::createFromPath($path);
        $this->reader->setDelimiter(',');
        $this->reader->setEnclosure('"');
        $this->reader->setEscape('\\');
        $this->reader->setEncodingFrom('iso-8859-15');
        $this->records = $records;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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
            if ($index == $this->records-1) break;
        }
    }
}

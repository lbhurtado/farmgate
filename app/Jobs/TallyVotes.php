<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\ElectionResultRepository;
use App\Repositories\ClusterRepository;
use App\Repositories\CandidateRepository;
use App\Entities\Candidate;
use App\Entities\Cluster;
use App\Instruction;

class TallyVotes extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $instruction;

    private $cluster;

    public function __construct(Instruction $instruction)
    {
        $this->instruction = $instruction;
        $this->cluster = $this->instruction->getShortMessage()->contact->cluster;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ElectionResultRepository $election_results, CandidateRepository $candidates)
    {
        preg_match_all("/(?<candidate>\w+)\s(?<votes>\d+)/", $this->instruction->getArguments(), $matches);

        $results = array_combine($matches['candidate'], $matches['votes']);

        foreach($results as $alias => $votes)
        {
            $candidate = $candidates->findByAlias($alias);
            if ($this->cluster)
                if ($candidate)
                    $election_results->createElectionResult(['votes' => 100], $candidate, $this->cluster);
        }


    }
}

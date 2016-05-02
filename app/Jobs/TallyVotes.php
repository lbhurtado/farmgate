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

    public function __construct(Instruction $instruction)
    {
        $this->instruction = $instruction;
    }

    protected function getCluster()
    {
        if (!$this->instruction->getShortMessage()->contact)
            return null;

        return $this->cluster = $this->instruction->getShortMessage()->contact->cluster;
    }

    /**
     * @param ElectionResultRepository $election_results
     * @param CandidateRepository $candidates
     */
    public function handle(ElectionResultRepository $election_results, CandidateRepository $candidates)
    {
        if (preg_match_all("/(?<candidate>\w+)\s(?<votes>\d+)/", $this->instruction->getArguments(), $matches))
        {
            $results = array_combine($matches['candidate'], $matches['votes']);
            foreach($results as $alias => $votes)
            {
                $candidate = $candidates->findByAlias($alias);
                $cluster = $this->getCluster();
                if ($cluster && $candidate)
                    $election_results->createElectionResult($votes, $candidate, $cluster);
            }
        }
    }
}

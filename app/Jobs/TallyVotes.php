<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\ElectionResultRepository;
use App\Repositories\CandidateRepository;
use App\Instruction;
use App\Events\PollResultsWereProcessed;

class TallyVotes extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $instruction;

    public function __construct(Instruction $instruction)
    {
        $this->instruction = $instruction;
    }

    protected function getClusterFromInstructionFromMessage()
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
        $text = $this->instruction->getArguments();
        if (preg_match_all("/(?<candidate>\w+)\s(?<votes>\d+)/", $text , $matches))
        {
            $poll_results = array_combine($matches['candidate'], $matches['votes']);
            $processed = [];
            foreach($poll_results as $alias => $votes)
            {
                $candidate = $candidates->findByAlias($alias);
                $cluster = $this->getClusterFromInstructionFromMessage();
                if ($cluster && $candidate)
                {
                    $result = $election_results->createElectionResult($votes, $candidate, $cluster);

                    if (!is_null($result))
                    {
                        $processed[$candidate->alias] = $votes;
                    }
                }
            }

            event(new PollResultsWereProcessed($this->instruction, $processed));
        }
    }
}

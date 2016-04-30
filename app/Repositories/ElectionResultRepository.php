<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use App\Entities\Candidate;
use App\Entities\Cluster;

/**
 * Interface ElectionResultRepository
 * @package namespace App\Repositories;
 */
interface ElectionResultRepository extends RepositoryInterface
{
    /**
     * Custom create for Election Result
     *
     * @param $attributes
     * @param Candidate $candidate
     * @param Cluster $cluster
     * @return mixed
     */
    public function createElectionResult($attributes, Candidate $candidate, Cluster $cluster);
}

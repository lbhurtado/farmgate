<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CandidateRepository
 * @package namespace App\Repositories;
 */
interface CandidateRepository extends RepositoryInterface
{
    /**
     * Get record by alias
     *
     * @param $alias
     * @return Candidate $candidate
     */
    public function findByAlias($alias);
}

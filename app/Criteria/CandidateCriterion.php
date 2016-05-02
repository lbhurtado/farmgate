<?php
/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 01/05/16
 * Time: 15:55
 */

namespace App\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;
use App\Entities\Candidate;

class CandidateCriterion implements CriteriaInterface
{
    private $candidate;

    /**
     * @param Candidate $candidate
     */
    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }


    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->with('candidate')->whereHas('candidate', function($q) {
            $q->where('id', '=', $this->candidate->id);
        });

        return $model;
    }

}
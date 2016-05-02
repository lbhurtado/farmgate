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

class CandidateCriterion implements CriteriaInterface
{
    private $alias;

    /**
     * CandidateCriterion constructor.
     * @param $alias
     */
    public function __construct($alias)
    {
        $this->alias = strtoupper($alias);
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
        $model = $model->with('candidate')->whereHas('candidate', function($q){
            $q->where('alias', '=', $this->alias);
        });

        return $model;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 02/05/16
 * Time: 23:18
 */

namespace App\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;
use App\Entities\Town;

class TownCriterion implements CriteriaInterface
{
    private $town;

    /**
     * @param Town $town
     */
    public function __construct(Town $town)
    {
        $this->town = $town;
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
        $model = $model->with('cluster')->whereHas('cluster', function($q)
        {
            $q->with('town')->whereHas('town', function($q)
            {
                $q->where('id', '=', $this->town->id);
            });
        });

        return $model;
    }

}
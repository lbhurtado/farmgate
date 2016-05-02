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

class TownCriterion implements CriteriaInterface
{
    private $town_name;

    /**
     * TownCriterion constructor.
     * @param $town_name
     */
    public function __construct($town_name)
    {
        $this->town_name = $town_name;
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
        $town_name = $this->town_name;

        $model = $model->with('cluster')->whereHas('cluster', function($q) use ($town_name)
        {
            $q->with('town')->whereHas('town',function($q) use ($town_name)
            {
                $q->where('name', '=', $town_name);
            });
        });

        return $model;
    }

}
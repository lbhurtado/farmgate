<?php
/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 06/05/16
 * Time: 06:52
 */

namespace App\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;
use App\Entities\Town;

class Town2Criterion implements CriteriaInterface
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
        $model = $model->with('town')->whereHas('town', function($q)
        {
            $q->where('id', '=', $this->town->id);
        });

        return $model;
    }
}
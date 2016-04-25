<?php
/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 25/04/16
 * Time: 13:39
 */

namespace App\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class IncomingShortMessageCriterion implements CriteriaInterface
{
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
        $model = $model->where('direction', '=', INCOMING);

        return $model;
    }

}
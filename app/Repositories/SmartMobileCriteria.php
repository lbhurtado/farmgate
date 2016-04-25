<?php
/**
 * Created by PhpStorm.
 * User: lbhurtado
 * Date: 25/04/16
 * Time: 10:19
 */

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class SmartMobileCriteria implements CriteriaInterface
{
    private $field;

    /**
     * SmartMobileCriteria constructor.
     * @param $field
     */
    public function __construct($field)
    {
        $this->field = $field;
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
        $field = $this->field;

        $model = $model->where(function($q) use ($field) {
            $q->where($field, 'LIKE', "%63918%")
                ->orWhere($field, 'LIKE', "%63919%")
                ->orWhere($field, 'LIKE', "%63920%");
        });
        return $model;
    }

}
<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ElectivePositionRepository;
use App\Entities\ElectivePosition;
use App\Validators\ElectivePositionValidator;

/**
 * Class ElectivePositionRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ElectivePositionRepositoryEloquent extends BaseRepository implements ElectivePositionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ElectivePosition::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ElectivePositionValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

//    public function findByField($field, $value = null, $columns = ['*'])
//    {
//        $this->applyCriteria();
//        $this->applyScope();
//        $value = preg_replace('/\s+/', '', strtoupper($value));
//        $model = $this->model->whereRaw("UPPER($field) = ?", [$value])->get($columns);
//        $this->resetModel();
//
//        return $this->parserResult($model);
//    }
}

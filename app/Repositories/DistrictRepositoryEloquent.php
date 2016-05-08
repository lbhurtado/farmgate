<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DistrictRepository;
use App\Presenters\DistrictPresenter;
use App\Entities\District;
use App\Validators\DistrictValidator;

/**
 * Class DistrictRepositoryEloquent
 * @package namespace App\Repositories;
 */
class DistrictRepositoryEloquent extends BaseRepository implements DistrictRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return District::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return DistrictValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Specify Presenter class name
     * @return mixed
     */
    public function presenter()
    {
        return DistrictPresenter::class;
    }
}

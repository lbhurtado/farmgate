<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\BarangayRepository;
use App\Validators\BarangayValidator;
use App\Presenters\BarangayPresenter;
use App\Entities\Barangay;

/**
 * Class BarangayRepositoryEloquent
 * @package namespace App\Repositories;
 */
class BarangayRepositoryEloquent extends BaseRepository implements BarangayRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Barangay::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return BarangayValidator::class;
    }

    /**
     * Specify Presenter class name
     * @return mixed
     */
    public function presenter()
    {
        return BarangayPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

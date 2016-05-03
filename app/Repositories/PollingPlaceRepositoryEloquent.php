<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PollingPlaceRepository;
use App\Entities\PollingPlace;
use App\Validators\PollingPlaceValidator;
use App\Presenters\PollingPlacePresenter;

/**
 * Class PollingPlaceRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PollingPlaceRepositoryEloquent extends BaseRepository implements PollingPlaceRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PollingPlace::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PollingPlaceValidator::class;
    }

    /**
     * Specify Presenter class name
     * @return mixed
     */
    public function presenter()
    {
        return PollingPlacePresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

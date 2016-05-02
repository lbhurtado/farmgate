<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\TownRepository;
use App\Validators\TownValidator;
use App\Presenters\TownPresenter;
use App\Entities\Town;

/**
 * Class TownRepositoryEloquent
 * @package namespace App\Repositories;
 */
class TownRepositoryEloquent extends BaseRepository implements TownRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Town::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TownValidator::class;
    }

    /**
     * Specify Presenter class name
     * @return mixed
     */
    public function presenter()
    {
        return TownPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

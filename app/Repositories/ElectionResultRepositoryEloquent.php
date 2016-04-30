<?php

namespace App\Repositories;

use App\Presenters\ElectionResultPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\ElectionResultRepository;
use App\Validators\ElectionResultValidator;
use App\Entities\ElectionResult;
use App\Entities\Candidate;
use App\Entities\Cluster;

/**
 * Class ElectionResultRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ElectionResultRepositoryEloquent extends BaseRepository implements ElectionResultRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ElectionResult::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ElectionResultValidator::class;
    }

    /**
     * Specify Presenter class name
     * @return mixed
     */
    public function presenter()
    {
        return ElectionResultPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Custom create for Election Result
     *
     * @param $attributes
     * @param Candidate $candidate
     * @param Cluster $cluster
     * @return mixed
     */
    public function createElectionResult($attributes, Candidate $candidate, Cluster $cluster)
    {
        $model = parent::create($attributes);
        $model->candidate()->associate($candidate);
        $model->cluster()->associate($cluster);
        $model->save();

        return $model;
    }


}

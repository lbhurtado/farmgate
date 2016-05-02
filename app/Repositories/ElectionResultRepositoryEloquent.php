<?php

namespace App\Repositories;

use App\Presenters\ElectionResultPresenter;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Validator\Contracts\ValidatorInterface;
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

    public function updateOrCreate(array $attributes, array $values = [])
    {
        $this->applyScope();

        if (!is_null($this->validator)) {
            $this->validator->with($values)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->model->updateOrCreate($attributes, $values);

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Custom create for Election Result
     *
     * @param $votes
     * @param Candidate $candidate
     * @param Cluster $cluster
     * @return mixed
     */
    public function createElectionResult($votes, Candidate $candidate, Cluster $cluster)
    {
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $candidate_id = $candidate->id;
        $cluster_id = $cluster->id;
        try
        {
            $model = $this->updateOrCreate(compact('candidate_id', 'cluster_id'), compact('votes'));
            $model->candidate()->associate($candidate);
            $model->cluster()->associate($cluster);
            $model->save();
        }
        catch (\Prettus\Validator\Exceptions\ValidatorException $e)
        {
            $model = null;
        }
        $this->skipPresenter($temporarySkipPresenter);

        return $model;
    }


}

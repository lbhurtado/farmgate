<?php

namespace App\Repositories;

use App\Presenters\CandidatePresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CandidateRepository;
use App\Entities\Candidate;
use App\Validators\CandidateValidator;

/**
 * Class CandidateRepositoryEloquent
 * @package namespace App\Repositories;
 */
class CandidateRepositoryEloquent extends BaseRepository implements CandidateRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Candidate::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return CandidateValidator::class;
    }

    /**
     * Specify Presenter class name
     * @return mixed
     */
    public function presenter()
    {
        return CandidatePresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ContactRepository;
use App\Entities\Contact;
use App\Validators\ContactValidator;
use App\Presenters\ContactPresenter;
use App\Mobile;

/**
 * Class ContactRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ContactRepositoryEloquent extends BaseRepository implements ContactRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Contact::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return ContactValidator::class;
    }

    /**
     * Specify Presenter class name
     * @return mixed
     */
    public function presenter()
    {
        return ContactPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function findByField($field, $value = null, $columns = ['*'])
    {
        if ($field == 'mobile')
        {
            $value = Mobile::number($value);
        }

        return parent::findByField($field, $value, $columns);
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        $attributes['mobile'] = Mobile::number($attributes['mobile']);

        return parent::updateOrCreate($attributes, $values);
    }
}

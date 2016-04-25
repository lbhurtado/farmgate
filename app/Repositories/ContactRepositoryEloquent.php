<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ContactRepository;
use App\Entities\Contact;
use App\Validators\ContactValidator;
use App\Presenters\ContactPresenter;
use libphonenumber\PhoneNumberFormat;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Repository\Events\RepositoryEntityUpdated;

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
            $value = phone_format($value, 'PH', PhoneNumberFormat::E164);
        }

        return parent::findByField($field, $value, $columns);
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        $this->applyScope();
        if (!is_null($this->validator)) {
            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $mobile = $attributes['mobile'];
        $handle = $attributes['handle'];
        $contact = $this->findByField('mobile', $mobile)->first();
        $model = $contact ? $this->update(compact('mobile', 'handle'), $contact->id) : $this->create(compact('mobile', 'handle'));

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();
        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }
}

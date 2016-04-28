<?php

namespace App\Repositories;

use App\Entities\Group;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TokenRepository;
use App\Entities\Token;
use App\Validators\TokenValidator;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use App\Entities\Contact;

/**
 * Class TokenRepositoryEloquent
 * @package namespace App\Repositories;
 */
class TokenRepositoryEloquent extends BaseRepository implements TokenRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Token::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TokenValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Populates the contact_id in tokens tables
     * and soft deletes the record
     *
     * @param Contact $contact
     * @param $code
     * @return mixed
     */
    function claim(Contact $contact, $code)
    {
        $token = $this->findByField('code', $code)->first()
            ->conjureObject()
            ->claimed_by($contact);
        $object = $token->getObject();
        $token->delete();

        return $object;
    }

    /**
     * Generate tokens given a collection
     * @param Collection $collection
     * @return mixed
     */
    function generate(Collection $collection)
    {
        $generator = new ComputerPasswordGenerator();

        $generator
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false)
            ->setAvoidSimilar(true)
            ->setLength(6)
        ;

        $collection->each(function($item, $key) use ($generator) {
            $this->create([
                'code'       => $generator->generatePassword(),
                'class'      => get_class($item),
                'reference'  => $key
            ]);
        });
    }
}

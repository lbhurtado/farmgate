<?php

namespace App\Repositories;

use App\Entities\Group;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TokenRepository;
use App\Entities\Token;
use App\Validators\TokenValidator;

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
     * Consume the token
     * @param $code
     * @return mixed
     */
    function claim($code)
    {
        $token = $this->findByField('code', $code)->first();
        $object = \App::make($token->class)->find($token->id);
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
//        $tokens = App::make(TokenRepository::class)->skipPresenter();

        $collection->each(function($item, $key){
            $this->create([
                'code'       => str_random(10),
                'class'      => get_class($item),
                'reference'  => $key
            ]);
        });
    }


}

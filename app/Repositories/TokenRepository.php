<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;
use App\Entities\Contact;

/**
 * Interface TokenRepository
 * @package namespace App\Repositories;
 */
interface TokenRepository extends RepositoryInterface
{

    /**
     * @param Contact $contact
     * @param $code
     * @return mixed
     */
    function claim(Contact $contact, $code);

    /**
     * Generate tokens given a collection
     * @param Collection $collection
     * @return mixed
     */
    function generate(Collection $collection);

    public function findByCodeInCodeAndPrecincts($field, $value = null, $columns = ['*']);
}

<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;
/**
 * Interface TokenRepository
 * @package namespace App\Repositories;
 */
interface TokenRepository extends RepositoryInterface
{
    /**
     * Consume the token
     * @param $code
     * @return mixed
     */
    function claim($code);

    /**
     * Generate tokens given a collection
     * @param Collection $collection
     * @return mixed
     */
    function generate(Collection $collection);
}

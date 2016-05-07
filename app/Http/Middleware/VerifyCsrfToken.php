<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Closure;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'sms',
        'sun'
    ];

    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     * @throws \Illuminate\Session\TokenMismatchException
     */
//    public function handle($request, Closure $next)
//    {
//        if ( ! $request->is('sms/*') )
//        {
//            return parent::handle($request, $next);
//        }
//
//        return $next($request);
//    }
}

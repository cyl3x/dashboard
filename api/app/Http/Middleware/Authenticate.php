<?php

namespace App\Http\Middleware;

use App\HelperService\Helpers;
use Closure;
use Exception;
use Illuminate\Contracts\Auth\Factory as Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Contracts\Encryption\DecryptException;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            if(! $token = (new Helpers())->getCookieToken($request)) return response('Unauthorized. Missing token.', 401);

            if(!JWTAuth::setToken($token)->authenticate()) return response('Unauthorized. Token Invalid', 401);
        } catch (DecryptException $e) {
            return response('Unauthorized. Payload is invalid.', 401);
        } catch (TokenExpiredException $e){
            return response('Unauthorized. Token as expired', 401);
        } catch (Exception $e){
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}

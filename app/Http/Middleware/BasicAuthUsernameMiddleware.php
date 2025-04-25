<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthUsernameMiddleware extends AuthenticateWithBasicAuth
{
    protected $auth;

    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Override the basic authentication to use 'username' and verify password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    protected function authenticateViaBasicAuth($request)
    {
        $guard = $this->auth->guard();

        $username = $request->getUser();
        $password = $request->getPassword();

        if (empty($username) || empty($password)) {
            return $this->unauthorizedResponse();
        }

        $user = $guard->getProvider()->retrieveByCredentials(['username' => $username]);

        if (!$user || !Hash::check($password, $user->getAuthPassword())) {
            return $this->unauthorizedResponse();
        }

        $guard->setUser($user);

        return null;
    }

    /**
     * Return unauthorized response.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthorizedResponse()
    {
        return response('Unauthorized', Response::HTTP_UNAUTHORIZED)
            ->header('WWW-Authenticate', 'Basic realm="Application"');
    }
}

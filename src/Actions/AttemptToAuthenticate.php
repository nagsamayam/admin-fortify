<?php

namespace NagSamayam\AdminFortify\Actions;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;
use NagSamayam\AdminFortify\Events\Login;
use NagSamayam\AdminFortify\Fortify;
use NagSamayam\AdminFortify\LoginRateLimiter;

class AttemptToAuthenticate
{
    public function __construct(
        protected StatefulGuard $guard,
        protected LoginRateLimiter $limiter
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        if (Fortify::$authenticateUsingCallback) {
            return $this->handleUsingCustomCallback($request, $next);
        }

        if ($this->guard->attempt(
            $request->only(Fortify::username(), 'password'),
            $request->filled('remember')
        )) {
            $admin = $request->user(config('admin_fortify.guard'));
            event(new Login($admin, $request->filled('remember')));

            return $next($request);
        }

        $this->throwFailedAuthenticationException($request);
    }

    /**
     * Attempt to authenticate using a custom callback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    protected function handleUsingCustomCallback($request, $next)
    {
        $user = call_user_func(Fortify::$authenticateUsingCallback, $request);

        if (! $user) {
            $this->fireFailedEvent($request);

            return $this->throwFailedAuthenticationException($request);
        }

        $this->guard->login($user, $request->filled('remember'));

        if ($user->is2faEnabled()) {
            $user->clearOtp();
        }

        event(new Login($user, $request->filled('remember')));

        return $next($request);
    }

    /**
     * Throw a failed authentication validation exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function throwFailedAuthenticationException($request)
    {
        $this->limiter->increment($request);

        throw ValidationException::withMessages([
            Fortify::username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function fireFailedEvent($request)
    {
        event(new Failed(config('admin_fortify.guard'), null, [
            Fortify::username() => $request->{Fortify::username()},
            'password' => $request->password,
        ]));
    }
}

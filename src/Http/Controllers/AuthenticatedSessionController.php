<?php

namespace NagSamayam\AdminFortify\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Pipeline;
use NagSamayam\AdminFortify\Actions\AttemptToAuthenticate;
use NagSamayam\AdminFortify\Actions\EnsureLoginIsNotThrottled;
use NagSamayam\AdminFortify\Actions\PrepareAuthenticatedSession;
use NagSamayam\AdminFortify\Actions\RedirectIfTwoFactorAuthenticatable;
use NagSamayam\AdminFortify\Contracts\LoginResponse;
use NagSamayam\AdminFortify\Contracts\LoginViewResponse;
use NagSamayam\AdminFortify\Contracts\LogoutResponse;
use NagSamayam\AdminFortify\Features;
use NagSamayam\AdminFortify\Fortify;
use NagSamayam\AdminFortify\Http\Requests\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    public function __construct(protected StatefulGuard $guard)
    {
    }

    /**
     * Show the login view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \NagSamayam\AdminFortify\Contracts\LoginViewResponse
     */
    public function create(Request $request): LoginViewResponse
    {
        return app(LoginViewResponse::class);
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param  \NagSamayam\AdminFortify\Http\Requests\LoginRequest  $request
     * @return mixed
     */
    public function store(LoginRequest $request)
    {
        return $this->loginPipeline($request)->then(function ($request) {
            return app(LoginResponse::class);
        });
    }

    /**
     * Get the authentication pipeline instance.
     *
     * @param  \NagSamayam\AdminFortify\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Pipeline\Pipeline
     */
    protected function loginPipeline(LoginRequest $request)
    {
        if (Fortify::$authenticateThroughCallback) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                call_user_func(Fortify::$authenticateThroughCallback, $request)
            ));
        }

        if (is_array(config('admin_fortify.pipelines.login'))) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                config('admin_fortify.pipelines.login')
            ));
        }

        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('admin_fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
            AttemptToAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ]));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \NagSamayam\AdminFortify\Contracts\LogoutResponse
     */
    public function destroy(Request $request): LogoutResponse
    {
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}

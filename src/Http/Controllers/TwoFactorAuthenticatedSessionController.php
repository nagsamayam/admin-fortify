<?php

namespace NagSamayam\AdminFortify\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NagSamayam\AdminFortify\Contracts\FailedTwoFactorLoginResponse;
use NagSamayam\AdminFortify\Contracts\TwoFactorChallengeViewResponse;
use NagSamayam\AdminFortify\Contracts\TwoFactorLoginResponse;
use NagSamayam\AdminFortify\Http\Requests\TwoFactorLoginRequest;
use NagSamayam\AdminFortify\Events\Login;

class TwoFactorAuthenticatedSessionController extends Controller
{
    public function __construct(protected StatefulGuard $guard)
    {
    }

    /**
     * Show the two factor authentication challenge view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \NagSamayam\AdminFortify\Contracts\TwoFactorChallengeViewResponse
     */
    public function create(Request $request): TwoFactorChallengeViewResponse
    {
        return app(TwoFactorChallengeViewResponse::class);
    }

    /**
     * Attempt to authenticate a new session using the two factor authentication code.
     *
     * @param  \NagSamayam\AdminFortify\Http\Requests\TwoFactorLoginRequest  $request
     * @return mixed
     */
    public function store(TwoFactorLoginRequest $request)
    {
        $user = $request->challengedUser($this->guard);

        if (! $request->hasValidCode($this->guard)) {
            return app(FailedTwoFactorLoginResponse::class);
        }

        $this->guard->login($user, $request->remember());

        $request->session()->regenerate();

        event(new Login($user, $request->remember()));

        return app(TwoFactorLoginResponse::class);
    }
}

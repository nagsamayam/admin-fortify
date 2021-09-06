<?php

namespace NagSamayam\AdminFortify\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NagSamayam\AdminFortify\Actions\ConfirmPassword;
use NagSamayam\AdminFortify\Contracts\ConfirmPasswordViewResponse;
use NagSamayam\AdminFortify\Contracts\FailedPasswordConfirmationResponse;
use NagSamayam\AdminFortify\Contracts\PasswordConfirmedResponse;

class ConfirmablePasswordController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**

     * Show the confirm password view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \NagSamayam\AdminFortify\Contracts\ConfirmPasswordViewResponse
     */
    public function show(Request $request)
    {
        return app(ConfirmPasswordViewResponse::class);
    }

    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function store(Request $request)
    {
        $confirmed = app(ConfirmPassword::class)(
            $this->guard,
            $request->user(config('admin_fortify.guard')),
            $request->input('password')
        );

        if ($confirmed) {
            $request->session()->put('admin_auth.password_confirmed_at', time());
        }

        return $confirmed
            ? app(PasswordConfirmedResponse::class)
            : app(FailedPasswordConfirmationResponse::class);
    }
}

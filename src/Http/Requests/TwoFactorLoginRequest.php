<?php

namespace NagSamayam\AdminFortify\Http\Requests;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use NagSamayam\AdminFortify\Contracts\FailedTwoFactorLoginResponse;
use NagSamayam\AdminFortify\Contracts\TwoFactorAuthenticationProvider;

class TwoFactorLoginRequest extends FormRequest
{
    /**
     * The user attempting the two factor challenge.
     *
     * @var mixed
     */
    protected $challengedUser;

    /**
     * Indicates if the user wished to be remembered after login.
     *
     * @var bool
     */
    protected $remember;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string',
        ];
    }

    /**
     * Determine if the request has a valid two factor code.
     *
     * @return bool
     */
    public function hasValidCode(StatefulGuard $guard)
    {
        return $this->code &&
            app(TwoFactorAuthenticationProvider::class)
            ->verify($this->challengedUser($guard)->getOtp(), $this->code);
    }

    /**
     * Determine if there is a challenged user in the current session.
     *
     * @return bool
     */
    public function hasChallengedUser(StatefulGuard $guard)
    {
        $model = $guard->getProvider()->getModel();

        dd($this->session()->has('admin_login.id'));

        return $this->session()->has('admin_login.id') &&
            $model::find($this->session()->get('admin_login.id'));
    }

    /**
     * Get the user that is attempting the two factor challenge.
     *
     * @return mixed
     */
    public function challengedUser(StatefulGuard $guard)
    {
        if ($this->challengedUser) {
            return $this->challengedUser;
        }
        $model = $guard->getProvider()->getModel();

        if (
            ! $this->session()->has('admin_login.id') ||
            ! $user = $model::find($this->session()->pull('admin_login.id'))
        ) {
            throw new HttpResponseException(
                app(FailedTwoFactorLoginResponse::class)->toResponse($this)
            );
        }

        return $this->challengedUser = $user;
    }

    /**
     * Determine if the user wanted to be remembered after login.
     *
     * @return bool
     */
    public function remember()
    {
        if (! $this->remember) {
            $this->remember = $this->session()->pull('admin_login.remember', false);
        }

        return $this->remember;
    }
}

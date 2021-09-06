<?php

namespace NagSamayam\AdminFortify\Http\Responses;

use Illuminate\Http\Response;
use NagSamayam\AdminFortify\Fortify;
use NagSamayam\AdminFortify\LoginRateLimiter;
use Illuminate\Validation\ValidationException;
use NagSamayam\AdminFortify\Contracts\LockoutResponse as LockoutResponseContract;

class LockoutResponse implements LockoutResponseContract
{
    /**
     * Create a new response instance.
     *
     * @param  \NagSamayam\AdminFortify\LoginRateLimiter  $limiter
     * @return void
     */
    public function __construct(protected LoginRateLimiter $limiter)
    {
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return with($this->limiter->availableIn($request), function ($seconds) {
            throw ValidationException::withMessages([
                Fortify::username() => [
                    trans('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ]),
                ],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        });
    }
}

<?php

namespace NagSamayam\AdminFortify\Actions;

use Illuminate\Auth\Events\Lockout;
use NagSamayam\AdminFortify\Contracts\LockoutResponse;
use NagSamayam\AdminFortify\LoginRateLimiter;

class EnsureLoginIsNotThrottled
{
    public function __construct(protected LoginRateLimiter $limiter)
    {
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
        if (! $this->limiter->tooManyAttempts($request)) {
            return $next($request);
        }

        event(new Lockout($request));

        return app(LockoutResponse::class);
    }
}

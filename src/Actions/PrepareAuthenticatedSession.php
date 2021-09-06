<?php

namespace NagSamayam\AdminFortify\Actions;

use NagSamayam\AdminFortify\LoginRateLimiter;

class PrepareAuthenticatedSession
{
    /**
     * Create a new class instance.
     *
     * @param  \NagSamayam\AdminFortify\LoginRateLimiter  $limiter
     * @return void
     */
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
        $request->session()->regenerate();

        $this->limiter->clear($request);

        return $next($request);
    }
}

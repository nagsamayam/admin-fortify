<?php

namespace NagSamayam\AdminFortify\Http\Responses;

use Illuminate\Http\JsonResponse;
use NagSamayam\AdminFortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use NagSamayam\AdminFortify\Fortify;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect()->intended(Fortify::redirects('login'));
    }
}

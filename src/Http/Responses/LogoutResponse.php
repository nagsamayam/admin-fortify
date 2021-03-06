<?php

namespace NagSamayam\AdminFortify\Http\Responses;

use Illuminate\Http\JsonResponse;
use NagSamayam\AdminFortify\Contracts\LogoutResponse as LogoutResponseContract;
use NagSamayam\AdminFortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): Response
    {
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect(Fortify::redirects('logout', '/'));
    }
}

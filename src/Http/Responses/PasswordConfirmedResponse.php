<?php

namespace NagSamayam\AdminFortify\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use NagSamayam\AdminFortify\Contracts\PasswordConfirmedResponse as PasswordConfirmedResponseContract;

class PasswordConfirmedResponse implements PasswordConfirmedResponseContract
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
                    ? new JsonResponse('', 201)
                    : redirect()->intended(config('admin_fortify.home'));
    }
}

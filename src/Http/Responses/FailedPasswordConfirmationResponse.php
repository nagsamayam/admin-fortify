<?php

namespace NagSamayam\AdminFortify\Http\Responses;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use NagSamayam\AdminFortify\Contracts\FailedPasswordConfirmationResponse as FailedPasswordConfirmationResponseContract;

class FailedPasswordConfirmationResponse implements FailedPasswordConfirmationResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): Response
    {
        $message = __('The provided password was incorrect.');

        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'password' => [$message],
            ]);
        }

        return redirect()->back()->withErrors(['password' => $message]);
    }
}

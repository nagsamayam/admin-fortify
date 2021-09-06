<?php

namespace NagSamayam\AdminFortify\Actions;

use NagSamayam\AdminFortify\Models\Admin;
use NagSamayam\AdminFortify\Jobs\SendAuthenticationVerificationOtp;

class SendAuthenticationVerificationOtpAction
{

    public function __construct(
        private SaveAuthenticationVerificationOtpAction $saveAuthenticationVerificationOtpAction,
    ) {
    }

    public function __invoke(Admin $admin): void
    {
        $admin = ($this->saveAuthenticationVerificationOtpAction)($admin);

        SendAuthenticationVerificationOtp::dispatch($admin);
    }
}

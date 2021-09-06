<?php

namespace NagSamayam\AdminFortify\Actions;

use NagSamayam\AdminFortify\Jobs\SendAuthenticationVerificationOtp;
use NagSamayam\AdminFortify\Models\Admin;

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

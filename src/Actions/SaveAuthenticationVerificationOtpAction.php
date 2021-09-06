<?php

namespace NagSamayam\AdminFortify\Actions;

use NagSamayam\AdminFortify\Models\Admin;
use NagSamayam\AdminFortify\TwoFactorAuthenticationProvider;

class SaveAuthenticationVerificationOtpAction
{

    public function __construct(protected TwoFactorAuthenticationProvider $provider)
    {
    }

    public function __invoke(Admin $admin): Admin
    {
        $admin->forceFill([
            'otp' => encrypt($this->provider->generateOtp()),
        ])->save();

        return $admin;
    }
}

<?php

namespace NagSamayam\AdminFortify\Actions;

use NagSamayam\AdminFortify\Models\Admin;
use NagSamayam\AdminFortify\Contracts\TwoFactorAuthenticationProvider;

class EnableTwoFactorAuthentication
{

    public function __construct(protected TwoFactorAuthenticationProvider $provider)
    {
    }

    public function __invoke(Admin $admin)
    {
        $admin->forceFill([
            'two_factor_enabled_at' => now(),
        ])->save();
    }
}

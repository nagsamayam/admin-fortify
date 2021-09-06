<?php

namespace NagSamayam\AdminFortify\Actions;

use NagSamayam\AdminFortify\Models\Admin;

class DisableTwoFactorAuthentication
{

    public function __invoke(Admin $admin)
    {
        $admin->forceFill([
            'two_factor_enabled_at' => null,
        ])->save();
    }
}

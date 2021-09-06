<?php

namespace NagSamayam\AdminFortify\Actions;

use NagSamayam\AdminFortify\Dtos\LoginLogData;
use NagSamayam\AdminFortify\Models\Admin;

class LogSuccessfulLogin
{
    public function __invoke(Admin $admin, LoginLogData $loginData)
    {
        $admin->logins()->create([
            'ip_address' => $loginData->ipAddress,
            'user_agent' => $loginData->userAgent,
            'login_at' => now(),
        ]);
    }
}

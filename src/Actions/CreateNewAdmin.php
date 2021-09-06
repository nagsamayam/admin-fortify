<?php

namespace NagSamayam\AdminFortify\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use NagSamayam\AdminFortify\Dtos\CreateNewAdminData;
use NagSamayam\AdminFortify\Jobs\SendWelcomeNotice;
use NagSamayam\AdminFortify\Models\Admin;

class CreateNewAdmin
{
    public function __invoke(CreateNewAdminData $adminData): Admin
    {
        $adminData = $adminData->withPassword(Str::random(12));

        $admin = Admin::create([
            'full_name' => $adminData->fullName,
            'email' => $adminData->email,
            'password' => Hash::make($adminData->password),
        ]);

        // Send Mail
        SendWelcomeNotice::dispatch($admin, $adminData->password);

        return $admin;
    }
}

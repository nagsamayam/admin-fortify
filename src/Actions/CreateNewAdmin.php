<?php

namespace NagSamayam\AdminFortify\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use NagSamayam\AdminFortify\Models\Admin;
use NagSamayam\AdminFortify\Dtos\CreateNewAdminData;
use NagSamayam\AdminFortify\Jobs\SendWelcomeNotice;

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

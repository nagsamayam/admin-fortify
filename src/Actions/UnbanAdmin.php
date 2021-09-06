<?php

namespace NagSamayam\AdminFortify\Actions;

use NagSamayam\AdminFortify\Models\Admin;

class UnbanAdmin
{
    public function __invoke(Admin $admin)
    {
        return $admin->unbanAdmin();
    }
}

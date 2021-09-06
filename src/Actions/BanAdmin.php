<?php

namespace NagSamayam\AdminFortify\Actions;

use NagSamayam\AdminFortify\Models\Admin;

class BanAdmin
{
    public function __invoke(Admin $admin)
    {
        return $admin->banAdmin();
    }
}

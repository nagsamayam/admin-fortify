<?php

namespace NagSamayam\AdminFortify\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use NagSamayam\AdminFortify\Models\Admin;

class Login
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Admin $admin, public bool $remember)
    {
    }
}

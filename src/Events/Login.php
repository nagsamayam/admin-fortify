<?php

namespace NagSamayam\AdminFortify\Events;

use Illuminate\Queue\SerializesModels;
use NagSamayam\AdminFortify\Models\Admin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class Login
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Admin $admin, public bool $remember)
    {
    }
}

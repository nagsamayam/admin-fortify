<?php

namespace NagSamayam\AdminFortify\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use NagSamayam\AdminFortify\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use NagSamayam\AdminFortify\Jobs\SendAdminLoginNotice;

class SendLoginNotification
{
    public function handle(Login $event)
    {
        SendAdminLoginNotice::dispatchIf($event->admin->enabledNofifyOnLogin(), $event->admin);
    }
}

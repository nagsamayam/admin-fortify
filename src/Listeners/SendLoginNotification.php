<?php

namespace NagSamayam\AdminFortify\Listeners;

use NagSamayam\AdminFortify\Events\Login;
use NagSamayam\AdminFortify\Jobs\SendAdminLoginNotice;

class SendLoginNotification
{
    public function handle(Login $event)
    {
        SendAdminLoginNotice::dispatchIf($event->admin->enabledNofifyOnLogin(), $event->admin);
    }
}

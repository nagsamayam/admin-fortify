<?php

namespace NagSamayam\AdminFortify\Listeners;

use Illuminate\Http\Request;
use NagSamayam\AdminFortify\Actions;
use Illuminate\Queue\InteractsWithQueue;
use NagSamayam\AdminFortify\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use NagSamayam\AdminFortify\Dtos\LoginLogData;

class LogSuccessfulLogin
{
    public function __construct(private Request $request, private Actions\LogSuccessfulLogin $action)
    {
    }
    public function handle(Login $event)
    {
        ($this->action)($event->admin, new LoginLogData([
            'ipAddress' => $this->request->ip(),
            'userAgent' => $this->request->userAgent(),
        ]));
    }
}

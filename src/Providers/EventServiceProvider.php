<?php

namespace NagSamayam\AdminFortify\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use NagSamayam\AdminFortify\Events\Login;
use NagSamayam\AdminFortify\Listeners\LogSuccessfulLogin;
use NagSamayam\AdminFortify\Listeners\SendLoginNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LogSuccessfulLogin::class,
            SendLoginNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}

<?php

namespace NagSamayam\AdminFortify\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use NagSamayam\AdminFortify\Models\Admin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use NagSamayam\AdminFortify\Mail\WelcomeNotification;

class SendWelcomeNotice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Admin $admin;

    public function __construct(Admin $admin, private string $password)
    {
        $this->admin = $admin->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->admin)->send(new WelcomeNotification($this->admin, $this->password));
    }
}

<?php

namespace NagSamayam\AdminFortify\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use NagSamayam\AdminFortify\Mail\WelcomeNotification;
use NagSamayam\AdminFortify\Models\Admin;

class SendWelcomeNotice implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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

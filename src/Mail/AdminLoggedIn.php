<?php

namespace NagSamayam\AdminFortify\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use NagSamayam\AdminFortify\Models\Admin;

class AdminLoggedIn extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Admin $admin)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(ucfirst(request()->getHttpHost()) . ' Login Notice')
            ->markdown('adminFortify::emails.login-notice');
    }
}

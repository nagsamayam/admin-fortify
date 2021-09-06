<?php

namespace NagSamayam\AdminFortify\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use NagSamayam\AdminFortify\Models\Admin;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminLoggedIn extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Admin $admin)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('Login Notice'))
            ->markdown('adminFortify::emails.login-notice');
    }
}

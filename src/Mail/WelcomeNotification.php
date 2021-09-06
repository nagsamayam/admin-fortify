<?php

namespace NagSamayam\AdminFortify\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use NagSamayam\AdminFortify\Models\Admin;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Admin $admin, private string $password)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('Account created'))
            ->markdown('adminFortify::emails.welcome')
            ->with(['password' => $this->password]);
    }
}

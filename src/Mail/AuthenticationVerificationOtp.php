<?php

namespace NagSamayam\AdminFortify\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use NagSamayam\AdminFortify\Models\Admin;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthenticationVerificationOtp extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        return $this->subject(ucfirst(request()->getHttpHost()) . ' 2FA Auth Code')
            ->markdown('adminFortify::emails.two-factor-otp');
    }
}

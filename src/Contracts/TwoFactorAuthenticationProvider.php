<?php

namespace NagSamayam\AdminFortify\Contracts;

interface TwoFactorAuthenticationProvider
{
    /**
     * Generate a new OTP.
     *
     * @return string
     */
    public function generateOtp(): string;

    /**
     * Verify the given otp.
     *
     * @param  string  $decryptedOtp
     * @param  string  $plainOtp
     * @return bool
     */
    public function verify(string $decryptedOtp, string $plainOtp): bool;
}

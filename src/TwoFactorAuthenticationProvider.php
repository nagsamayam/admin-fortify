<?php

namespace NagSamayam\AdminFortify;

use Illuminate\Support\Str;
use NagSamayam\AdminFortify\Contracts\TwoFactorAuthenticationProvider as TwoFactorAuthenticationProviderContract;

class TwoFactorAuthenticationProvider implements TwoFactorAuthenticationProviderContract
{
    public function generateOtp(): string
    {
        return Str::random(8);
    }

    public function verify(string $decryptedOtp, string $plainOtp): bool
    {
        return $decryptedOtp === $plainOtp;
    }
}

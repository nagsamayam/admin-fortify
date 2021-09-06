<?php

namespace NagSamayam\AdminFortify\Dtos;

use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class LoginLogData extends DataTransferObject
{
    public ?string $ipAddress;

    public ?string $userAgent;
}

<?php

namespace NagSamayam\AdminFortify\Dtos;

use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class CreateNewAdminData extends DataTransferObject
{
    public string $fullName;

    public string $email;

    public ?string $password;


    public function withPassword(string $password): self
    {
        $clone = clone $this;

        $clone->password = $password;

        return $clone;
    }
}

<?php

namespace App\Domain;

class UserAlreadyPresent implements \App\Domain\ResultInterface
{
    private $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getMessage(): string
    {
        return "Unable to add. User '{$this->username}' is already present.";
    }
}

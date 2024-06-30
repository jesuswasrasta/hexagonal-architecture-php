<?php

namespace App\Domain;

class UserAdded implements ResultInterface
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getMessage(): string
    {
        return "User '{$this->username}' has been added successfully.";
    }
}

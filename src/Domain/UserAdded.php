<?php
declare(strict_types=1);

namespace App\Domain;

class UserAdded implements ResultInterface
{
    private Username $username;

    public function __construct(Username $username)
    {
        $this->username = $username;
    }

    public function getMessage(): string
    {
        return "User '{$this->username}' has been added successfully.";
    }
}

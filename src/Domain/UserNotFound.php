<?php
declare(strict_types=1);

namespace App\Domain;

class UserNotFound implements \App\Domain\ResultInterface
{
    private Username $username;

    public function __construct(Username $username)
    {
        $this->username = $username;
    }

    public function getMessage(): string
    {
        return "Unable to remove. User '{$this->username}' not found.";
    }
}

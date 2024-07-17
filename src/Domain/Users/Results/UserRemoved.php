<?php
declare(strict_types=1);

namespace App\Domain\Users\Results;

use App\Domain\Users\Username;

class UserRemoved implements ResultInterface
{
    private Username $username;

    public function __construct(Username $username)
    {
        $this->username = $username;
    }

    public function getMessage(): string
    {
        return "User '{$this->username}' has been removed successfully.";
    }
}

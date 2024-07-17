<?php
declare(strict_types=1);

namespace App\Domain\Users\Results;

use App\Domain\Users\Username;

class UserAlreadyPresent implements ResultInterface
{
    private Username $username;

    public function __construct(Username $username)
    {
        $this->username = $username;
    }

    public function getMessage(): string
    {
        return "Unable to add. User '{$this->username}' is already present.";
    }
}

<?php
declare(strict_types=1);

namespace App\Domain\Users\Results;

use App\Domain\Users\Username;
use App\Shared\Domain\Aggregate\ResultInterface;

class UserNotFound implements ResultInterface
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

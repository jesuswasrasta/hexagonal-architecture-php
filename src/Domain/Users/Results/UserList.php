<?php
declare(strict_types=1);

namespace App\Domain\Users\Results;

use App\Domain\Users\Username;

class UserList implements ResultInterface
{
    private array $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function getMessage(): string
    {
        return json_encode($this->users);
    }
}

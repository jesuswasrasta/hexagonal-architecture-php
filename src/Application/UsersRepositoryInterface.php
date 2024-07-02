<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\Users;

interface UsersRepositoryInterface
{
    /**
     * Reads and returns an array of users from storage.
     *
     * @return Users|bool An array of users if successful, false otherwise.
     */
    public function readUsers(): Users|bool;

    /**
     * Writes the provided users to a storage.
     *
     * @param Users $users An array of users to be written.
     *
     * @return void
     */
    public function writeUsers(Users $users) : void;
}

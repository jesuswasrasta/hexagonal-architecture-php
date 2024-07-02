<?php
declare(strict_types=1);

namespace App\Domain;

/**
 * Interface UsersInterface
 *
 * Represents a user management interface.
 */
interface UsersInterface
{
    /**
     * Adds a username to the application.
     *
     * @param Username $username The username to add.
     * @return ResultInterface The result of the operation.
     */
    public function add(Username $username): ResultInterface;

    /**
     * Converts the object to an array representation.
     *
     * @return array<string> The array representation of the object.
     */
    public function toStringArray(): array;
}

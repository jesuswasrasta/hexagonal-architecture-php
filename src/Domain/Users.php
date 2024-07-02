<?php
declare(strict_types=1);

namespace App\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;

/**
 * Class Users
 *
 * Represents a collection of users.
 *
 * Users diventa una `Entity`.
 * E come entità, viene eletta a `AggregateRoot` dell'aggregato `Users`.
 * Guarda i commenti nelle classi
 *
 * Guarda i commenti in @link AggregateRoot e @link EntityBase
 */

class Users extends AggregateRoot implements UsersInterface
{
    /**
     * @var array<Username>
     */
    private array $users;

    private function __construct(UsersId $id)
    {
        $this->id = $id;
        $this->users = [];
    }

    public static function create(UsersId $id): self
    {
        $course = new self($id);
        return $course;
    }

    public function id(): UsersId
    {
        return $this->id;
    }

    /**
     * Adds a user to the collection if it doesn't already exist.
     *
     * @param Username $username The username of the user to add.
     * @return ResultInterface The result of the operation.
     */
    public function add(Username $username): ResultInterface
    {
        if ($this->exists($username)) {
            return new UserAlreadyPresent($username);
        } else {
            $this->users[] = $username;
            return new UserAdded($username);
        }
    }

    /**
     * Check if a username exists in the users array.
     *
     * @param Username $username The username to check
     * @return bool Returns true if the username exists, false otherwise
     */
    private function exists(Username $username): bool
    {
        return in_array($username, $this->users);
    }

    /**
     * @param array<string> $users
     * @return void
     */
    public function addFromStringArray(array $users = []): void
    {
        // Questo metodo che prende stringhe mi faceva comodo qui per adesso...
        // Ma non è un metodo che dovrebbe stare in questa classe.
        // Inoltre non c'è validazione di stringhe, è soggetto a bug.
        // Più avanti lo toglieremo di mezzo ;)
        foreach ($users as $user) {
            $this->add(new Username($user));
        }
    }

    /**
     * @param Users $users
     * @return void
     */
    public function addRange(Users $users): void
    {
        $this->addFromStringArray($users->toStringArray());
    }

    /**
     * Converts Username array to a string array.
     *
     * @return array<String> The array representation of the object.
     */
    public function toStringArray(): array
    {
        // Questo metodo che restituisce stringhe mi faceva comodo qui per adesso...
        // Ma non è un metodo che dovrebbe stare in questa classe.
        // Più avanti lo toglieremo di mezzo ;)
        return array_map(function (Username $username) {
            return $username->__toString();
        }, $this->users);
    }
}


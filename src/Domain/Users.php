<?php
declare(strict_types=1);

namespace App\Domain;

use App\Shared\Domain\Aggregate\AggregateInterface;
use App\Shared\Domain\Aggregate\AggregateRoot;


// Un aggregato è un insieme di Entity e Value Object che concorrono a uno scopo.
// Tra le entità che compongono l'agggregato, una viene eletta ad AggregateRoot.
// Sarà lei che accetterà comandi dall'esterno e li smisterà alle entità interne.
// Sarà  lei che garantirà che le invarianti di dominio siano rispettate.
//
// Users è una Entity.
// Nel nostro semplicissimo caso,
// Users basta per gestire la logica di dominio legata agli utenti.
//
// Users diventa quindi un Aggregate.
// E siccome ogni Aggregate ha un AggregateRoot, è anche AggregateRoot
/**
 * Guarda i commenti in @link AggregateRoot e @link AggregateInterface
 */

class Users extends AggregateRoot implements AggregateInterface
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
        $users = new self($id);
        return $users;
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
            return $username->value();
        }, $this->users);
    }
}


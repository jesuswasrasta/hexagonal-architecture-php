<?php
declare(strict_types=1);

namespace App\Application;

// Interfaccia che astra il concetto di archivio degli utenti
// A livello Application userò sempre questa inetrfaccia,
// non mi interessa concretamente com'è implementata,
// ne se salva su file di testo, JSON, MySql o altro 😄
interface UsersArchiveInterface
{
    /**
     * Reads and returns an array of users from storage.
     *
     * @return array<string>|bool An array of users if successful, false otherwise.
     */
    public function readUsers(): array|bool;

    /**
     * Writes the provided users to a storage.
     *
     * @param array<string> $users An array of users to be written.
     *
     * @return void
     */
    public function writeUsers(array $users) : void;
}

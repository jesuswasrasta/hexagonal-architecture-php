<?php

namespace App\Application;

use Exception;

class WelcomeService
{
    private UsersArchiveInterface $usersArchive;

    public function __construct(UsersArchiveInterface $usersArchive)
    {
        $this->usersArchive = $usersArchive;
    }

    public function welcomeUser($username)
    {
        if ($this->checkIfUserExists($username)) {
            return "Welcome back, " . $username . "!";
        } else {
            $this->addUser($username);
            return "Welcome, " . $username . "!";
        }
    }

    private function checkIfUserExists($username)
    {
        $users = $this->usersArchive->readUsers();

        return in_array($username, $users);
    }

    /**
     * @throws Exception
     */
    private function addUser($username)
    {
        // Leggo gli utenti dall'archivio
        $users = $this->usersArchive->readUsers();

        // Verifico se l'utente esiste (non dovrebbe...)
        if (in_array($username, $users)) {
            throw new Exception("User already exists in the archive.");
        }

        // Metto l'user nell'array
        array_push($users, $username);

        // Scrivo l'array su file
        $this->usersArchive->writeUsers($users);
    }
}

?>

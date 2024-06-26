<?php

namespace App\Application;

use App\Domain\UserAdded;
use App\Domain\UserAlreadyPresent;
use App\Domain\Users;
use App\Domain\UsersInterface;

class WelcomeService
{
    private UsersInterface $users;
    private UsersArchiveInterface $usersArchive;

    public function __construct(UsersArchiveInterface $usersArchive)
    {
        $this->usersArchive = $usersArchive;
        $this->users = new Users($this->usersArchive->readUsers());
    }

    public function welcomeUser($username): string
    {
        $result = $this->users->add($username);

        if ($result instanceof UserAdded) {
            $this->usersArchive->writeUsers($this->users->toArray());
            return "Welcome, " . $username . "!";
        }
        elseif ($result instanceof UserAlreadyPresent) {
            return "Welcome back, " . $username . "!";
        }
        else{
            return $result->getMessage();
        }

        // Questo servizio ora delega la logica di gestione degli utenti al Dominio,
        // com'è giusto che sia 😉
        // Lui ora fa solo da orchestratore: chiede al Dominio di aggiungere un utente,
        // visto che lui sa quando si possono aggiungere (ovvero solo se non esistono).
        // Riceve una risposta, che qui ho chiamato `Result`, di tipo differente
        // a seconda dell'esito: se nuovo utente o se utente già presente.
        // Ricevuto l'esito, comanda al'archivio di salvare la nuova
        // lista degli utenti, se necessario.
        // Ora la logica di dominio è completamente separata
        // e avulsa dal mondo esterno, e può essere riutilizzata ion più occasioni.
    }
}

?>

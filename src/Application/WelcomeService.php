<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\UserAdded;
use App\Domain\UserAlreadyPresent;
use App\Domain\Username;

class WelcomeService
{
    private UsersRepositoryInterface $usersRepository;

    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function welcomeUser(string $user): string
    {
        // Carica user esistenti
        $users = $this->usersRepository->readUsers();
        if ($users !== false) {
            $users->addRange($users);
        }

        $username = new Username($user);
        $result = $users->add($username);

        if ($result instanceof UserAdded) {
            $this->usersRepository->writeUsers($users);
            return "Welcome, " . $user . "!";
        }
        elseif ($result instanceof UserAlreadyPresent) {
            return "Welcome back, " . $user . "!";
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

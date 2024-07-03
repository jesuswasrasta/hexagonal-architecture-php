<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\UserAdded;
use App\Domain\UserAlreadyPresent;
use App\Domain\Username;
use App\Domain\UsersId;
use App\Shared\Domain\Repository\RepositoryInterface;

class WelcomeService
{
    private RepositoryInterface $usersRepository;

    public function __construct(RepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function welcomeUser(string $user): string
    {
        // Ora questo servizio carica l'aggregato Users attraverso un Repository.
        // Un Repository ha come compito di caricare e slavare un Aggregato.
        //
        // Qui, per convenzione, il Repository legge e scrive
        // su un file chiamato come l'id dell'aggregato.
        //
        // NB: come già scritto nei commenti del Repository,
        // in questa prima implementazione abbiamo un Repository
        // che legge e scrive i dati dell'aggregato, e viene
        // usato pari-pari anche per restituire dati al servizio.
        //
        // Questo NON è il normale comportamento di un repository
        // nell'approccio DDD che in genere uso, è una semplificazione
        // per non introdurre troppi concetti nuovi tutti insieme.
        //
        // Più avanti vedremo che il Repository avrà il solo compito
        // di caricare e salvare l'aggregato, mentre per scrivere
        // e leggere i dati da fornire al mondo esterno ci sarà un
        // altro strumento, il ReadModel, con i suoi Persister.
        //
        // A tendere avremo un WriteModel separato dal ReadModel! 🤯
        $domainId = new UsersId("d9208ab6-6402-49e9-a61d-111111111111");
        $users = $this->usersRepository->getById($domainId);

        $username = new Username($user);
        $result = $users->add($username);

        if ($result instanceof UserAdded) {
            $this->usersRepository->save($users);
            return "Welcome, " . $user . "!";
        }
        elseif ($result instanceof UserAlreadyPresent) {
            return "Welcome back, " . $user . "!";
        }
        else{
            return $result->getMessage();
        }
    }
}

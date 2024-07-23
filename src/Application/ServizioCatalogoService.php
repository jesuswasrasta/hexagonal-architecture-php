<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\Services\DescrizioneServizio;
use App\Domain\Services\IdServizio;
use App\Domain\Services\TitoloServizio;
use App\Domain\Users\Results\UserAdded;
use App\Domain\Users\Results\UserAlreadyPresent;
use App\Domain\Users\SubscriptionDate;
use App\Domain\Users\Username;
use App\Domain\Users\Users;
use App\Domain\Users\UsersId;
use App\Shared\Domain\Repository\RepositoryInterface;

class ServizioCatalogoService
{
    private RepositoryInterface $catalogoServizioRepository;

    public function __construct(RepositoryInterface $catalogoServizioRepository)
    {
        $this->catalogoServizioRepository = $catalogoServizioRepository;
    }

    public function aggiungiServizio(TitoloServizio $titolo, DescrizioneServizio $descrizione): string
    {

        $servizioId = new IdServizio('1');
        $servizio = $this->catalogoServizioRepository->getById($servizioId);

        $result = $servizio->addServizio($titolo,$descrizione);

        if ($result instanceof UserAdded) {
            $this->catalogoServizioRepository->save($users);
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

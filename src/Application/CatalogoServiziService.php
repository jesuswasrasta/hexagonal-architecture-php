<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\Services\CatalogoServizi;
use App\Domain\Services\CatalogoServiziId;
use App\Domain\Services\DescrizioneServizio;
use App\Domain\Services\IdServizio;
use App\Domain\Services\Results\ServizioAdded;
use App\Domain\Services\Results\ServizioAlreadyPresent;
use App\Domain\Services\ServiceStatus;
use App\Domain\Services\Servizio;
use App\Domain\Services\TitoloServizio;
use App\Shared\Domain\Repository\RepositoryInterface;

class CatalogoServiziService
{
    private RepositoryInterface $catalogoServizioRepository;

    public function __construct(RepositoryInterface $catalogoServizioRepository)
    {
        $this->catalogoServizioRepository = $catalogoServizioRepository;
    }

    public function aggiungiServizio(string $titolo, string $descrizione): string
    {

        $titolo = new TitoloServizio($titolo);
        $descrizione = new DescrizioneServizio($descrizione);
        $catalogoServiziId = new CatalogoServiziId('d9208ab6-6402-49e9-a61d-111111111112');
        $catalogoServizi = $this->catalogoServizioRepository->getById($catalogoServiziId);

        $servizioId = IdServizio::random();
        //$servizio = Servizio::newServizio($titolo, $descrizione);
        $servizio = new Servizio($servizioId, $titolo, $descrizione, new ServiceStatus());

        //Devo aggiungere il mio servizio al catalogo
        $result = $catalogoServizi->addServizio($servizio);
        if ($result instanceof ServizioAdded) {
            $this->catalogoServizioRepository->save($catalogoServizi);
            return "Servizio aggiunto con successo!";
        }else if ($result instanceof ServizioAlreadyPresent) {
            return "Servizio già presente!";
        }else{
            return $result->getMessage();
        }


    }
}

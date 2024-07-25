<?php

namespace App\Domain\Services;


use App\Domain\Services\Results\ServizioAdded;
use App\Domain\Services\Results\ServizioAlreadyPresent;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Aggregate\ResultInterface;

class CatalogoServizi extends AggregateRoot
{

    private array $servizi = [];

    private function __construct(CatalogoServiziId $id)
    {
        $this->id = $id;
    }

    public function id(): CatalogoServiziId
    {
        return $this->id;
    }

    public function addServizio(Servizio $servizio): ResultInterface
    {
        if ($this->exists($servizio)) {
            return new ServizioAlreadyPresent($servizio);
        }
        $this->servizi[] = $servizio;
        return new ServizioAdded($servizio);

    }

    public function exists(Servizio $servizio): bool
    {
        foreach ($this->servizi as $s) {
            if ($s->id() == $servizio->id()) {
                return true;
            }
        }
        return false;
    }

    public function getServizio(IdServizio $getId): Servizio
    {
        foreach ($this->servizi as $servizio) {
            if ($servizio->id() == $getId) {
                return $servizio;
            }
        }
        throw new ServizioNotFoundException('Servizio non trovato');
    }
    public function getAllServizi(): array
    {
        $serviziDTO = [];
        foreach ($this->servizi as $servizio) {
            $serviziDTO[] = $servizio->getDto();
        }
        return $serviziDTO;
    }

    public static function create(CatalogoServiziId $id): self
    {
        return new self($id);
    }
}

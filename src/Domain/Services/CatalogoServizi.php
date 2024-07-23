<?php

namespace App\Domain\Services;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Aggregate\DomainIdInterface;

class CatalogoServizi extends AggregateRoot
{

    private array $servizi = [];

    public function id(): DomainIdInterface
    {
        // TODO: Implement id() method.
    }

    public function addServizio(Servizio $servizio): void
    {
        $this->servizi[] = $servizio;
    }

    public function getServizio(DomainIdInterface $getId): Servizio
    {
        foreach ($this->servizi as $servizio) {
            if ($servizio->id() == $getId) {
                return $servizio;
            }
        }
        throw new ServizioNotFoundException('Servizio non trovato');
    }
}

<?php

namespace App\Domain\Services;

use App\Shared\Domain\Aggregate\DomainIdInterface;
use App\Shared\Domain\Aggregate\EntityBase;

class Servizio extends EntityBase
{

    public function __construct(IdServizio $id, private TitoloServizio $titolo, private DescrizioneServizio $descrizione, private readonly ServiceStatus $status)
    {
        $this->id = $id;

    }

    public function id(): DomainIdInterface
    {
        return $this->id;
    }
    public function getStatus(): ServiceStatus
    {
        return $this->status;
    }
    public function getDto(): array
    {
        return [$this->id->value(), $this->titolo->value(), $this->descrizione->value(), $this->status->value()];
    }
}

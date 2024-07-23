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

    public function toStringArray(): array
    {
        return array_map(function (array $values) {
            return $values[0]->value().' => '.$values[1]->value().' => '.$values[2]->value().' => '.$values[3]->value();
        }, $this->servizio);
    }
}

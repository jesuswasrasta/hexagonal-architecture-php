<?php

namespace App\Domain\Services;

use App\Shared\Domain\Aggregate\DomainIdInterface;
use App\Shared\Domain\Aggregate\EntityBase;

class Servizio extends EntityBase
{
    private ServiceStatus $status;

    public function __construct(IdServizio $id)
    {
        $this->id = $id;
        $this->status = new ServiceStatus();
    }

    public function id(): DomainIdInterface
    {
        return $this->id;
    }

    public function getStatus(): ServiceStatus
    {
        return $this->status;
    }
}

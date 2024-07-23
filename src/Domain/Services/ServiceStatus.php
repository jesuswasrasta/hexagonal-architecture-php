<?php

namespace App\Domain\Services;

use App\Shared\Domain\ValueObject\ValueObject;

class ServiceStatus extends ValueObject
{
    private StatiServizio $status;

    public function __construct()
    {
        $this->status = StatiServizio::Disattivo;
    }

    public function value(): StatiServizio
    {
        return $this->status;
    }
    public function equals(ServiceStatus $status): bool
    {
        return $this->status === $status->status;
    }
}

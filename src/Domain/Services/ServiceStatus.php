<?php

namespace App\Domain\Services;

use App\Shared\Domain\ValueObject\ValueObject;

class ServiceStatus extends ValueObject
{


    public function __construct(private readonly StatiServizio $status = StatiServizio::Disattivo)
    {
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

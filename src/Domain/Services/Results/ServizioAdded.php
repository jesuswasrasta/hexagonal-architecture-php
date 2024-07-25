<?php
declare(strict_types=1);

namespace App\Domain\Services\Results;

use App\Domain\Services\Servizio;
use App\Shared\Domain\Aggregate\ResultInterface;

class ServizioAdded implements ResultInterface
{
    private Servizio $servizio;

    public function __construct(Servizio $servizio)
    {
        $this->servizio = $servizio;
    }

    public function getMessage(): string
    {
        return "Servizio '{$this->servizio}' has been added successfully.";
    }
}

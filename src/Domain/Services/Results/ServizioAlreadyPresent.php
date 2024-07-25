<?php
declare(strict_types=1);

namespace App\Domain\Services\Results;

use App\Domain\Services\Servizio;
use App\Shared\Domain\Aggregate\ResultInterface;

class ServizioAlreadyPresent implements ResultInterface
{
    private Servizio $servizio;

    public function __construct(Servizio $servizio)
    {
        $this->servizio = $servizio;
    }

    public function getMessage(): string
    {
        return "Unable to add. Servizio '{$this->servizio}' is already present.";
    }
}

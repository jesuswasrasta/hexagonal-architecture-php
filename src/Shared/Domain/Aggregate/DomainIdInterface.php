<?php
declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

interface DomainIdInterface
{
    public function value(): mixed;
}

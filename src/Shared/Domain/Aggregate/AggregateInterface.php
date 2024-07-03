<?php
declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

interface AggregateInterface
{
    public function id(): DomainIdInterface;
}

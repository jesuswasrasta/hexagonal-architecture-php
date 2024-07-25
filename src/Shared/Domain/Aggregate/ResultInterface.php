<?php
declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

interface ResultInterface
{
    public function getMessage(): string;
}

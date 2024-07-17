<?php
declare(strict_types=1);

namespace App\Domain\Users\Results;

interface ResultInterface
{
    public function getMessage(): string;
}

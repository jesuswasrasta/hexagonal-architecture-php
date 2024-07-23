<?php

namespace App\Domain\Services;

use App\Shared\Domain\ValueObject\ValueObject;
use InvalidArgumentException;

class DescrizioneServizio extends ValueObject
{

    private string $descrizione;

    public function __construct(string $descrizione)
    {
        if (empty($descrizione)) {
            throw new InvalidArgumentException('The descrizione cannot be empty');
        }
        if (strlen($descrizione) > 300) {
            throw new InvalidArgumentException('String must be mx 300chars');
        }

        $this->descrizione = $descrizione;
    }

    public function equals(DescrizioneServizio $descrizione): bool
    {
        return $this->descrizione === $descrizione->descrizione;
    }

    public function __toString(): string
    {
        return $this->descrizione;
    }

    public function value(): string
    {
        return $this->descrizione;
    }
}

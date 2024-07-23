<?php

namespace App\Domain\Services;

use App\Shared\Domain\ValueObject\ValueObject;
use InvalidArgumentException;

class TitoloServizio extends ValueObject
{

    private string $titolo;

    public function __construct(string $titolo)
    {
        if (empty($titolo)) {
            throw new InvalidArgumentException('The titolo cannot be empty');
        }
        if (strlen($titolo) > 25) {
            throw new InvalidArgumentException('String must be mx 25chars');
        }
        if (!preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $titolo))
        {
            throw new InvalidArgumentException('Invalid kebab-case string.');
        }

        $this->titolo = $titolo;
    }

    public function equals(TitoloServizio $titolo): bool
    {
        return $this->titolo === $titolo->titolo;
    }

    public function __toString(): string
    {
        return $this->titolo;
    }

    public function value(): string
    {
        return $this->titolo;
    }
}

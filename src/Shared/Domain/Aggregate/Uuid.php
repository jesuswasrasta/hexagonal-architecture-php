<?php
declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Stringable;

// Classe base per gestire dei generici id basati su UUID.
// 99% delle volte un UUID va bene per definire un id di una entità.
// Si può anche fare una stringa, o inventarsi qualcosa di custom,
// ma un UUID è sempre una buona scelta 😄

abstract class Uuid implements Stringable
{
    final public function __construct(protected string $value)
    {
        $this->ensureIsValidUuid($value);
    }

    final public static function random(): self
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    final public function value(): string
    {
        return $this->value;
    }

    final public function equals(self $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', self::class, $id));
        }
    }
}

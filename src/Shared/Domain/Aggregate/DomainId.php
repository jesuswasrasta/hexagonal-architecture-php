<?php
declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Stringable;

// Classe base per rinforzare il concetto di id delle entità
// (compresi aggregati e aggregate root)
//
// Uso Uuid per comodità e per abitudine 😉
abstract class DomainId implements DomainIdInterface,Stringable
{
    final public function __construct(protected string $value)
    {
        $this->ensureIsValidId($value);
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

    private function ensureIsValidId(string $id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', self::class, $id));
        }
    }
}

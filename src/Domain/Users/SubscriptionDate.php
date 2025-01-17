<?php
declare(strict_types=1);

namespace App\Domain\Users;

use App\Shared\Domain\ValueObject\ValueObject;
use InvalidArgumentException;

final class SubscriptionDate extends ValueObject
{
    private \DateTime $subDate;

    public function __construct(\DateTime $subDate)
    {
        if ($subDate > new \DateTime()) {
            throw new InvalidArgumentException('The date cannot be in the future');
        }
        $this->subDate = $subDate;
    }

    public function equals(SubscriptionDate $subDate): bool
    {
        return $this->subDate === $subDate->value();
    }

    public function value(): \DateTime
    {
        return $this->subDate;
    }

    public function getYearMonth(): string
    {
        return $this->subDate->format('Y-m');
    }
}

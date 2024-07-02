<?php
declare(strict_types=1);

namespace App\Domain;

use App\Shared\Domain\Aggregate\EntityId;

// Ogni entità deve avere un identificatore unico.
// Gli id sono Value Object.
final class UsersId extends EntityId
{
}

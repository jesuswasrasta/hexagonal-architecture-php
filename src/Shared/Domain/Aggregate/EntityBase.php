<?php
declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

// Classe base che rappresenta un'entità di dominio.
// Ogni entità di dominio deve avere un identificatore univoco.
// Fare una classe astratta per il solo id può sembrare eccessivo,
// ma ci permette di rendere esplicito il concetto.
// Andando avanti, sapremo che ogni oggetto che eredita da EntityBase
// è una entità, e questo ci semplificherà la vita 😊

abstract class EntityBase
{
    protected EntityId $id;

    abstract public function id(): EntityId;
}

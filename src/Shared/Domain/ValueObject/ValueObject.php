<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

// Classe base che rappresenta un Value Object (VO).
// Ogni VO rappresenta un dato presente e significativo nel dominio.
// Fare una classe astratta per il solo id può sembrare eccessivo,
// ma ci permette di rendere esplicito il concetto.
// Andando avanti, sapremo che ogni oggetto che eredita da ValueObject
// è una Value Object, e questo ci semplificherà la vita 😊
abstract class ValueObject
{
    protected string $value;

    // Possiamo usare questo generico metodo per ottenere il valore di un VO.
    // Sarà spesso una rappresentazione testuale, un JSON, etc.
    abstract public function value(): mixed;
}

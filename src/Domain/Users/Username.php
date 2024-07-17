<?php
declare(strict_types=1);

namespace App\Domain\Users;

use App\Shared\Domain\ValueObject\ValueObject;
use InvalidArgumentException;

// Questo è un Value Object (VO).
//
// I VO si usano per incapsulare un concetto di dominio.
//
// La loro caratteristiche principali sono:
// - Due VO sono identici se tutte le loro proprietà sono identiche
// - Sono immutabili
// - Sono validi per definizione
//
// Pensiamo al concetto di `Username`: una stringa è una stringa, può essere qualsiasi cosa...
// Ma un nome utente è un nome utente, ha delle regole, ad esempio non può essere vuoto o duplicato,
// non può contenere simboli, blanks, ideogrammi giapponesi... 😄.
// Queste regole sono definite dal dominio, e sono regole che devono essere rispettate.
// La creazione di un VO (fatta da costruttore, builder o factory che sia) garantisce che il VO sia sempre valido.
//
// I VO sono immutabili, il che significa che una volta creati,
// non possono essere modificati.
//
// `Username` è un esempio di VO, ma se ne possono creare quanti se ne vogliono (e si deve farlo!).
// Ad esempio, si può creare un VO `Email`, un VO `Indirizzo`, ecc.
// In questo modo si garantisce che ogni concetto di dominio sia sempre espresso,
// e che le regole di ogni concetto siano sempre rispettate.
//
// Per ora ci accontentiamo di questo esempio semplice.
// Più avanti magari aggiungiamo altre regole di dominio per `Username` come la lunghezza,
// caratteri accettabili, ecc.

final class Username extends ValueObject
{
    private string $username;

    public function __construct(string $username)
    {
        // Qui aggiungeremo altre regole di dominio,
        // come la lunghezza massima, i caratteri accettabili, ecc.
        // Per ora ci accontentiamo di questa regola.
        if (empty($username)) {
            throw new InvalidArgumentException('The username cannot be empty');
        }

        $this->username = $username;
    }

    // Molto importante ricordarsi di implementare il metodo `equals()`!
    // I Value Object sono uguali dal momento che tutte le loro proprietà sono uguali.
    // Dobbiamo essere sicuri che due oggetti Username siano uguali se il loro username è uguale.
    public function equals(Username $username): bool
    {
        return $this->username === $username->username;
    }

    public function __toString(): string
    {
        return $this->username;
    }

    public function value(): string
    {
        return $this->username;
    }
}

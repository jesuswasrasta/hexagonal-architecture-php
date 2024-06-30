<?php

namespace App\Domain;


class Users implements UsersInterface
{
    /**
     * @var array<string>
     */
    private array $users;

    /**
     * @param array<string> $users
     */
    public function __construct(array $users = [])
    {
        $this->users = $users;
    }

    /**
     * Adds a user to the collection if it doesn't already exist.
     *
     * @param string $username The username of the user to add.
     * @return ResultInterface The result of the operation.
     */
    public function add(string $username): ResultInterface
    {
        // Qui l'oggetto rinforza le cosiddette **invarianti**,
        // le regole di business che devono essere rispettate.
        // Nel nostro caso, non si può aggiungere un utente se esiste già.
        // Uso poi il cossiddetto `Result Pattern`, ovvero,
        // come risultato di un'operazione do un particolare tipo di oggetto,
        // un po' come si fa fa 'alla vecchia mnaiera' coi result code.
        // Il vantaggio rispetto ai result code è che la risposta
        // è un oggetto tipizzato, non una stringa o un numero...
        // Non può 'essere confuso', non può essere null,
        // non devo fare nessuna validazione 😏

        if ($this->exists($username)) {
            return new UserAlreadyPresent($username);
        } else {
            $this->users[] = $username;
            return new UserAdded($username);
        }
    }

    /**
     * Converts the object to an array.
     *
     * @return array<string> The array representation of the object.
     */
    public function toArray(): array
    {
        return $this->users;
    }

    /**
     * Check if a username exists in the users array.
     *
     * @param string $username The username to check
     * @return bool Returns true if the username exists, false otherwise
     */
    private function exists(string $username): bool
    {
        return (in_array($username, $this->users));
    }
}
?>

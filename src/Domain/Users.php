<?php

namespace App\Domain;


class Users implements UsersInterface
{
    private array $users;

    public function __construct(array $users = [])
    {
        $this->users = $users;
    }

    public function add($username): ResultInterface
    {
        // Qui l'oggetto rinforza le cosiddette **invarianti**,
        // le regole di business che devono essere rispettate.
        // Nel nostro caso, non si può aggiungere un utente se esiste già.
        // Uso poi il cossiddetto `Result Pattern`, ovvero,
        // come risultato di un'operazione do un particolare tipo di oggetto,
        // un po' come si fa fa 'alla vecchia mnaiera' coi result code.
        // Il vantaggio rispetto ai result code è che la risposta
        // è un oggetto tipizzato, non una stringa o un numero...
        // Non può 'essere confuso', non pu`ó essere null,
        // non devo fare nessuna validazione 😏

        if ($this->exists($username)) {
            return new UserAlreadyPresent($username);
        } else {
            $this->users[] = $username;
            return new UserAdded($username);
        }
    }

    public function toArray(): array
    {
        return $this->users;
    }

    private function exists($username): bool
    {
        return (in_array($username, $this->users));
    }
}
?>

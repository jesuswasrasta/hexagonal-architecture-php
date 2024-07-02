<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\UsersRepositoryInterface;
use App\Domain\Users;
use App\Domain\UsersId;

// È una delle implementazioni possibili dell'archivio utenti
// Per adesso mi sta bene un file; se domani ho bisogno di un DB,
// Scrivo l'implementazione concreta di questa interfaccia che
// insiste su un DB e inietto quella, senza dover toccare una riga
// dellla logica applicativa 😉

class FileUsersRepository implements UsersRepositoryInterface
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }


    /**
     * @return Users|bool
     */
    public function readUsers(): Users|bool
    {
        // Create file if not exists
        if (!file_exists($this->filename)) {
            file_put_contents($this->filename, "");
        }

        $users = Users::create(UsersId::random());
        // Check if file exists before trying to read
        if (file_exists($this->filename)) {
            $fileContent = file($this->filename, FILE_IGNORE_NEW_LINES);
            if ($fileContent !== false) {
                $users->addFromStringArray($fileContent);
            } else {
                return false;
            }
        }
        return $users;
    }

    public function writeUsers(Users $users): void
    {
        file_put_contents($this->filename, implode("\n", $users->toStringArray()));
    }
}
?>

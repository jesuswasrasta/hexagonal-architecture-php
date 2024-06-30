<?php

namespace App\Infrastructure;

use App\Application\UsersArchiveInterface;

// È una delle implementazioni possibili dell'archivio utenti
// Per adesso mi sta bene un file; se domani ho bisogno di un DB,
// Scrivo l'implementazione concreta di questa interfaccia che
// insiste su un DB e inietto quella, senza dover toccare una riga
// dellla logica applicativa 😉

class FileUsersArchive implements UsersArchiveInterface
{
    private string $filename;

    /**
     * @var string[]
     */
    private array $users = array();


    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }


    /**
     * @return string[]|bool
     */
    public function readUsers(): array|bool
    {
        // Check if file exists before trying to read
        if (file_exists($this->filename)) {
            $this->users = file($this->filename, FILE_IGNORE_NEW_LINES);
        }
        return $this->users;
    }

    public function writeUsers(array $users): void
    {
        file_put_contents($this->filename, implode("\n", $users));
    }
}
?>

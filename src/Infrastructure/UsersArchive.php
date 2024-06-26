<?php

namespace App\Infrastructure;

class UsersArchive
{
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;

        if (!file_exists($this->filePath)) {
            touch($this->filePath);
        }
    }

    public function addUser($name): string
    {
        $fileContent = file_get_contents($this->filePath);

        if (str_contains($fileContent, $name.PHP_EOL)) {
            return 'Welcome back '  . $name . '!';
        } else {
            file_put_contents($this->filePath, $name.PHP_EOL, FILE_APPEND);
            return 'Welcome ' . $name . '!';
        }

        /*
          OK, ho separato la logica dal comando, ma sto ancora mischiando cose...
          La logica di verifica utente è accoppiata al salvataggio su file...
          Quando domani salverò su DB che faccio?! 😅
          Tempo di separare la logica di dominio dall'infrastruttura 😉
         * */
    }
}

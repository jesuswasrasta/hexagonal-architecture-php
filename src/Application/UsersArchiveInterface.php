<?php

namespace App\Application;

// Interfaccia che astra il concetto di archivio degli utenti
// A livello Application userò sempre questa inetrfaccia,
// non mi interessa concretamente com'è implementata,
// ne se salva su file di testo, JSON, MySql o altro 😄
interface UsersArchiveInterface
{
    public function readUsers();

    public function writeUsers($users);
}

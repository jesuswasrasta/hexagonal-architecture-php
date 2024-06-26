<?php

namespace App\UserInterface\Cli\Command;

use App\Application\WelcomeService;
use App\Infrastructure\FileUsersArchive;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//Questo è un comando nel contesto delle applicazioni a riga di comando
final class WelcomeCommand extends Command
{
    public function __construct()
    {
        parent::__construct('app:welcome');
    }

    //Qui è dove si definiscono proprietà del comando (argomenti, etc.)
    protected function configure(): void
    {
        $this
            ->setDescription('Welcomes users.')
            ->setHelp('This command says "Welcome!" to new users, or "Welcome back!" to known users.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the user.');
    }

    //Qui è dove il comando viene eseguito
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //Leggo l'argomento passato, il nome dell'utente
        $name = $input->getArgument('name');

        //Eseguo l'azione
        $usersArchive = new FileUsersArchive("Users.txt");
        // Ho introdotto un servizio a livello Application, `WelcomeService`
        // D'ora in poi sarà lui ad occuparsi di orchestrare
        // la richiesta che arriva dal comando.
        // La logica del comando tornerà a esse `stupida`:
        // instanzio servizio e lo chiamo, ci pensa lui.
        //
        // Per fare il suo lavora, il `WelcomeService` si appoggia
        // a un generico `Archive`, un oggetto che persite i dati.
        // Ora ne ho creato uno che persiste su file di testo, come prima.
        // Se domani voglio farne uno che persiste su JSON,
        // mi basta implementarlo e iniettarlo qui al posto del FileUsersArchive.
        // Non dovrò più cambiare il `WelcomeService` per salvare in altra modo 😏
        //
        // Ora qui sto costruendo e iniettando a mano questo Archive;
        // a tendere potrei definirlo da configurazione e/o usare
        // un registry di dependency injection.
        $welcomeService = new WelcomeService($usersArchive);
        $msg = $welcomeService->welcomeUser($name);
        $output->writeln($msg);

        return self::SUCCESS;
    }
}

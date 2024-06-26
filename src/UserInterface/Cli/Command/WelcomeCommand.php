<?php

namespace App\UserInterface\Cli\Command;

use App\Infrastructure\UsersArchive;
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
        /*
         Questo comando è solamente uno dei punti di accesso della mia applicazione
         Non voglio che ci sia logica di business, ma al massimo logica di validazione:
         verifico ad es. se il parametro del comando esiste ed è corretto,
         ma la logica voglio che stia da un'altra parte, separata.
         Questo mi permette di usarla in più situazioni, di poter evolverla
         senza cambiare il comando, di cambiare il comando senza cambiare la logica.
         Disaccoppiamneto è la parola d'ordine! :)
         * */
        $usersArchive = new UsersArchive('Users.txt');
        $result = $usersArchive->addUser($name);

        $output->writeln($result);

        return self::SUCCESS;
    }
}

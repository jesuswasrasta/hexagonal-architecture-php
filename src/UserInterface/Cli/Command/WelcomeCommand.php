<?php

namespace App\UserInterface\Cli\Command;

use App\Application\WelcomeService;
use App\Infrastructure\FileUsersArchive;
use App\Infrastructure\JsonUsersArchive;
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
        //$usersArchive = new FileUsersArchive("Users.txt");
        // 🆕 Usiamo un Json!
        $usersArchive = new JsonUsersArchive("Users.json");

        $welcomeService = new WelcomeService($usersArchive);
        $msg = $welcomeService->welcomeUser($name);
        $output->writeln($msg);

        return self::SUCCESS;
    }
}
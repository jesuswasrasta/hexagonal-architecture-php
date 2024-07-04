<?php
declare(strict_types=1);

namespace App\UserInterface\Cli\Command;

use App\Application\GoodByeService;
use App\Infrastructure\FileUsersRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//Questo è un comando nel contesto delle applicazioni a riga di comando
final class GoodByeCommand extends Command
{
    public function __construct()
    {
        parent::__construct('app:goodbye');
    }

    //Qui è dove si definiscono proprietà del comando (argomenti, etc.)
    protected function configure(): void
    {
        $this
            ->setDescription('Goodbye users.')
            ->setHelp('This command says "Good bye!" agli utenti che ci salutano.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the user.');
    }

    //Qui è dove il comando viene eseguito
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //Leggo l'argomento passato, il nome dell'utente
        /** @var string $name */
        $name = $input->getArgument('name');

        //Eseguo l'azione
        $usersRepository = new FileUsersRepository();

        // 🆕 Usiamo un Json!
        //$usersRepository = new JsonUsersRepository("Users.json");

        $goodByeService = new GoodByeService($usersRepository);
        $msg = $goodByeService->goodByeUser($name);
        $output->writeln($msg);

        return self::SUCCESS;
    }
}

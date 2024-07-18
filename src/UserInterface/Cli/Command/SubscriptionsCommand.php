<?php
declare(strict_types=1);

namespace App\UserInterface\Cli\Command;

use App\Application\SubscriptionsService;
use App\Infrastructure\FileUsersRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//Questo è un comando nel contesto delle applicazioni a riga di comando
final class SubscriptionsCommand extends Command
{
    public function __construct()
    {
        parent::__construct('app:subscriptions');
    }

    //Qui è dove si definiscono proprietà del comando (argomenti, etc.)
    protected function configure(): void
    {
        $this->addArgument('date', InputArgument::REQUIRED, 'Mese e anno');
    }

    //Qui è dove il comando viene eseguito
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //Leggo l'argomento passato, il nome dell'utente
        /** @var string $date */
        $date = $input->getArgument('date');

        //Eseguo l'azione
        $usersRepository = new FileUsersRepository();

        $subscriptionsService = new SubscriptionsService($usersRepository);
        $msg = $subscriptionsService->listSubscribedUsers($date);
        $output->writeln($msg);

        return self::SUCCESS;
    }
}

<?php
declare(strict_types=1);

namespace App\UserInterface\Cli\Command;

use App\Application\CatalogoServiziService;
use App\Application\WelcomeService;
use App\Infrastructure\FileUsersRepository;
use App\Infrastructure\JsonServizioRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//Questo è un comando nel contesto delle applicazioni a riga di comando
final class CatalogoServiziCommand extends Command
{
    public function __construct()
    {
        parent::__construct('app:add-servizio');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Aggiungi servizio')
            ->addArgument('titolo', InputArgument::REQUIRED, 'Il titolo del servizio')
            ->addArgument('descrizione', InputArgument::REQUIRED, 'La desc del servizio');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $titolo = $input->getArgument('titolo');
        $descrizione = $input->getArgument('descrizione');

        $repository = new JsonServizioRepository();

        $servizio = new CatalogoServiziService($repository);
        $msg = $servizio->aggiungiServizio($titolo,$descrizione);
        $output->writeln($msg);

        return self::SUCCESS;
    }
}

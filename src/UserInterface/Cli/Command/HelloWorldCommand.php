<?php

namespace App\UserInterface\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/*
   Il componente Console di Symfony facilita la creazione di interfacce a riga di comando.
   Per creare un comando, si deve definire una classe che estende `Symfony\Component\Console\Command\Command`
   Questa classe deve implementare il metodo `configure()` per definire i parametri e le opzioni del comando,
   e il metodo `execute()` per la logica applicativa.
   Il comando è registrato come servizio con il tag `console.command`.
   Una volta registrato, il comando può essere eseguito dalla riga di comando
   utilizzando il comando `bin/console`.
 * */

//Questo è un comando nel contesto delle applicazioni a riga di comando
final class HelloWorldCommand extends Command
{
    public function __construct()
    {
        parent::__construct('app:helloworld');
    }

    //Qui è dove si definiscono proprietà del comando (argomenti, etc.)
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the one running the example command.');
    }

    //Qui è dove il comando viene eseguito
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        $output->writeln("<info>Hello world, my name is $name.</info>");

        return self::SUCCESS;

        /*
        Approfondimento:
        - execute() e configure() sono metodi di cui viene fatto overriding: vediamo che significa
         * */
    }
}

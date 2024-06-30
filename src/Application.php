<?php
declare(strict_types=1);

namespace App;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Traversable;

final class Application extends BaseApplication
{
    /**
     * @param Traversable<Command> $commands
     */
    public function __construct(Traversable $commands)
    {
        $this->addCommands(iterator_to_array($commands));

        parent::__construct(__NAMESPACE__, '1.0.0');
    }
}

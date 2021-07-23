<?php

declare(strict_types=1);

namespace App\Cli\Commands\Cache;

use Config\General;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function exec;

class ClearOrmCacheCommand extends Command
{
    public function __construct(private General $config)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'cache:clear-orm');

        $this->setDescription(description: 'Clears the ORM cache');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(messages: '<fg=yellow>Clearing ORM cache...</>');

        $storagePath = $this->config->pathToStorageDirectory();

        $ormCachePath = $storagePath . '/doctrine/*';

        exec(command: 'rm -rf ' . $ormCachePath);

        $output->writeln(messages: '<fg=green>ORM cache cleared</>');

        return 0;
    }
}

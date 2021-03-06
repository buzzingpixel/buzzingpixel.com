<?php

declare(strict_types=1);

namespace App\Cli\Commands\Cache;

use Config\General;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function exec;

class ClearDiCacheCommand extends Command
{
    public function __construct(private General $config)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'cache:clear-di');

        $this->setDescription(description: 'Clears the compiled DI cache');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(messages: '<fg=yellow>Clearing DI cache...</>');

        $storagePath = $this->config->pathToStorageDirectory();

        $diCachePath = $storagePath . '/di-cache/*';

        exec(command: 'rm -rf ' . $diCachePath);

        $output->writeln(messages: '<fg=green>DI cache cleared</>');

        return 0;
    }
}

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
    private General $config;

    public function __construct(General $config)
    {
        parent::__construct();

        $this->config = $config;
    }

    protected function configure(): void
    {
        $this->setName('cache:clear-di');

        $this->setDescription('Clears the compiled DI cache');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=yellow>Clearing DI cache...</>');

        $storagePath = $this->config->pathToStorageDirectory();

        $diCachePath = $storagePath . '/di-cache/*';

        exec('rm -rf ' . $diCachePath);

        $output->writeln('<fg=green>DI cache cleared</>');

        return 0;
    }
}

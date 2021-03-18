<?php

declare(strict_types=1);

namespace App\Cli\Commands\Cache;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearDatabaseCacheCommand extends Command
{
    public function __construct(private CacheItemPoolInterface $cacheItemPool)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'cache:clear-database');

        $this->setDescription(description: 'Clears the database cache');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=yellow>Clearing database cache...</>');

        $this->cacheItemPool->clear();

        $output->writeln('<fg=green>Database cache cleared</>');

        return 0;
    }
}

<?php

declare(strict_types=1);

namespace App\Cli\Commands\Cache;

use App\Cli\Services\CliQuestion;
use App\Context\DatabaseCache\CacheItemPool;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearDatabaseCacheByKeyCommand extends Command
{
    public function __construct(
        private CacheItemPool $cacheItemPool,
        private CliQuestion $cliQuestion,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'cache:clear-database-by-key');

        $this->setDescription(description: 'Clears the database cache by key');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cacheItemPool->deleteItem($this->cliQuestion->ask(
            '<fg=cyan>Key to delete: </>'
        ));

        $output->writeln('<fg=green>Specified cache cleared</>');

        return 0;
    }
}

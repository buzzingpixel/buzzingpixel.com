<?php

declare(strict_types=1);

namespace App\Cli\Commands\Cache;

use App\Context\RedisCache\CacheItemPool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearRedisCacheCommand extends Command
{
    public function __construct(private CacheItemPool $cacheItemPool)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'cache:clear-redis');

        $this->setDescription(description: 'Clears the redis cache');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=yellow>Clearing redis cache...</>');

        $this->cacheItemPool->clear();

        $output->writeln('<fg=green>Redis cache cleared</>');

        return 0;
    }
}

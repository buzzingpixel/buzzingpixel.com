<?php

declare(strict_types=1);

namespace App\Cli\Commands\Cache;

use App\Http\ServiceSuites\StaticCache\StaticCacheApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearStaticCacheCommand extends Command
{
    public function __construct(private StaticCacheApi $staticCacheApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'cache:clear-static');

        $this->setDescription(description: 'Clears the static cache');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(messages: '<fg=yellow>Clearing static cache...</>');

        $this->staticCacheApi->clearStaticCache();

        $output->writeln(messages: '<fg=green>Static cache cleared</>');

        return 0;
    }
}

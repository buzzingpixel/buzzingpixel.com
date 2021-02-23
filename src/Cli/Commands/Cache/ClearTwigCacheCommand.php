<?php

declare(strict_types=1);

namespace App\Cli\Commands\Cache;

use App\Http\ServiceSuites\TwigCache\TwigCacheApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearTwigCacheCommand extends Command
{
    public function __construct(private TwigCacheApi $twigCacheApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'cache:clear-twig');

        $this->setDescription(description: 'Clears the twig cache');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(messages: '<fg=yellow>Clearing twig cache...</>');

        if (! $this->twigCacheApi->clearTwigCache()) {
            $output->writeln(messages: '<fg=green>Twig cache not enabled</>');

            return 0;
        }

        $output->writeln(messages: '<fg=green>Twig cache cleared</>');

        return 0;
    }
}

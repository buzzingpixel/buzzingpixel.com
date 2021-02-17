<?php

declare(strict_types=1);

namespace App\Cli\Commands\Cache;

use App\Http\ServiceSuites\TwigCache\TwigCacheApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearTwigCacheCommand extends Command
{
    private TwigCacheApi $twigCacheApi;

    public function __construct(TwigCacheApi $twigCacheApi)
    {
        parent::__construct();

        $this->twigCacheApi = $twigCacheApi;
    }

    protected function configure(): void
    {
        $this->setName('cache:clear-twig');

        $this->setDescription('Clears the twig cache');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=yellow>Clearing twig cache...</>');

        if (! $this->twigCacheApi->clearTwigCache()) {
            $output->writeln('<fg=green>Twig cache not enabled</>');

            return 0;
        }

        $output->writeln('<fg=green>Twig cache cleared</>');

        return 0;
    }
}

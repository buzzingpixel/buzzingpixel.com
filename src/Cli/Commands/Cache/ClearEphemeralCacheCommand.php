<?php

declare(strict_types=1);

namespace App\Cli\Commands\Cache;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearEphemeralCacheCommand extends Command
{
    private ClearDiCacheCommand $clearDiCache;
    private ClearStaticCacheCommand $clearStaticCache;
    private ClearTwigCacheCommand $clearTwigCache;

    public function __construct(
        ClearDiCacheCommand $clearDiCache,
        ClearStaticCacheCommand $clearStaticCache,
        ClearTwigCacheCommand $clearTwigCache
    ) {
        parent::__construct();

        $this->clearDiCache     = $clearDiCache;
        $this->clearStaticCache = $clearStaticCache;
        $this->clearTwigCache   = $clearTwigCache;
    }

    protected function configure(): void
    {
        $this->setName(name: 'cache:clear-ephemeral');

        $this->setDescription(
            description: 'Clears static and twig, and DI caches'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->clearDiCache->execute(input: $input, output: $output);

        $this->clearStaticCache->execute(input: $input, output: $output);

        $this->clearTwigCache->execute(input: $input, output: $output);

        return 0;
    }
}

<?php

declare(strict_types=1);

namespace App\Cli\Commands\ImportFromOldSite;

use App\ImportFromOldSite\OrphanedLicenses\OrphanedLicenses;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Import004OrphanedLicensesCommand extends Command
{
    public function __construct(private OrphanedLicenses $orphanedLicenses)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('import-from-old-site:004-orphaned-licenses');
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $this->orphanedLicenses->import();

        return 0;
    }
}

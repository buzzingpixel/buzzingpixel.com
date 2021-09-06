<?php

declare(strict_types=1);

namespace App\Cli\Commands\ImportFromOldSite;

use App\ImportFromOldSite\Issues\Issues;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Import003IssuesCommand extends Command
{
    public function __construct(private Issues $issues)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('import-from-old-site:003-issues');
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $this->issues->import();

        return 0;
    }
}

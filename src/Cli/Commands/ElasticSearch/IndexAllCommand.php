<?php

declare(strict_types=1);

namespace App\Cli\Commands\ElasticSearch;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexAllCommand extends Command
{
    public function __construct(
        private IndexIssuesCommand $indexIssues,
        private IndexUsersCommand $indexUsers,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('elastic-search:index-all');

        $this->addOption(
            'enqueue',
            'e',
            null,
            'Enqueue items instead of processing inline',
        );
    }

    public function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $this->indexIssues->execute($input, $output);

        $this->indexUsers->execute($input, $output);

        return 0;
    }
}

<?php

declare(strict_types=1);

namespace App\Cli\Commands\ElasticSearch;

use App\Context\ElasticSearch\ElasticSearchApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexAllIssuesCommand extends Command
{
    public function __construct(private ElasticSearchApi $elasticSearchApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('elastic-search:index-all-issues');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(messages: '<fg=yellow>Indexing issues...</>');

        $this->elasticSearchApi->indexAllIssues();

        $output->writeln(messages: '<fg=green>Finished indexing issues.</>');

        return 0;
    }
}

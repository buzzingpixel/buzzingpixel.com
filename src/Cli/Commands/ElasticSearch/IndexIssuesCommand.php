<?php

declare(strict_types=1);

namespace App\Cli\Commands\ElasticSearch;

use App\Context\ElasticSearch\ElasticSearchApi;
use App\Context\ElasticSearch\QueueActions\IndexAllIssuesQueueAction;
use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\QueueApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexIssuesCommand extends Command
{
    public function __construct(
        private QueueApi $queueApi,
        private ElasticSearchApi $elasticSearchApi,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('elastic-search:index-issues');

        $this->addOption(
            'enqueue',
            'e',
            null,
            'Enqueue issue indexing instead of processing inline',
        );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        if ($input->getOption('enqueue') === true) {
            $output->writeln(
                messages: '<fg=yellow>Enqueuing issue indexing...</>',
            );

            $this->queueApi->addToQueue(
                queue: (new Queue())
                    ->withHandle(handle: 'index-all-issues')
                    ->withAddedQueueItem(
                        newQueueItem: new QueueItem(
                            className: IndexAllIssuesQueueAction::class,
                        ),
                    ),
            );

            $output->writeln(
                messages: '<fg=green>Issue indexing added to queue</>',
            );

            return 0;
        }

        $output->writeln(messages: '<fg=yellow>Indexing issues...</>');

        $this->elasticSearchApi->indexIssues();

        $output->writeln(messages: '<fg=green>Finished indexing issues.</>');

        return 0;
    }
}

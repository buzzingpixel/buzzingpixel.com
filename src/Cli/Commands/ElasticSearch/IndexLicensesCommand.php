<?php

declare(strict_types=1);

namespace App\Cli\Commands\ElasticSearch;

use App\Context\ElasticSearch\ElasticSearchApi;
use App\Context\ElasticSearch\QueueActions\IndexLicenseQueueAction;
use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\QueueApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IndexLicensesCommand extends Command
{
    public function __construct(
        private QueueApi $queueApi,
        private ElasticSearchApi $elasticSearchApi,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('elastic-search:index-licenses');

        $this->addOption(
            'enqueue',
            'e',
            null,
            'Enqueue license indexing instead of processing inline',
        );
    }

    public function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        if ($input->getOption('enqueue') === true) {
            $output->writeln(
                messages: '<fg=yellow>Enqueuing license indexing...</>',
            );

            $this->queueApi->addToQueue(
                queue: (new Queue())
                    ->withHandle(handle: 'index-issues')
                    ->withAddedQueueItem(
                        newQueueItem: new QueueItem(
                            className: IndexLicenseQueueAction::class,
                        ),
                    ),
            );

            $output->writeln(
                messages: '<fg=green>License indexing added to queue</>',
            );

            return 0;
        }

        $output->writeln(messages: '<fg=yellow>Indexing licenses...</>');

        $this->elasticSearchApi->indexLicenses();

        $output->writeln(messages: '<fg=green>Finished indexing licenses.</>');

        return 0;
    }
}

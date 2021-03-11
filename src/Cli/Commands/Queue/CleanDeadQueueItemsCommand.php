<?php

declare(strict_types=1);

namespace App\Cli\Commands\Queue;

use App\Context\Queue\QueueApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanDeadQueueItemsCommand extends Command
{
    public function __construct(private QueueApi $queueApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('queue:clean-dead-items');

        $this->setDescription('Cleans dead queue items');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=yellow>Cleaning dead items...</>');

        $numberCleaned = $this->queueApi->cleanDeadQueues();

        if ($numberCleaned < 1) {
            $output->writeln('<fg=green>There were no dead items</>');

            return 0;
        }

        if ($numberCleaned === 1) {
            $output->writeln(
                '<fg=green>1 dead item was cleaned</>'
            );

            return 0;
        }

        $output->writeln(
            '<fg=green>' . $numberCleaned . ' dead items were cleaned</>'
        );

        return 0;
    }
}

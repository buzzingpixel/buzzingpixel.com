<?php

declare(strict_types=1);

namespace App\Cli\Commands\Queue;

use App\Context\Queue\QueueApi;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class RunQueueCommand extends Command
{
    public function __construct(
        private QueueApi $queueApi,
        private LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('queue:run');

        $this->setDescription('Runs the next available item in the queue');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Queue command is running next item in queue');

        $item = $this->queueApi->fetchNextQueueItem();

        if ($item === null) {
            $msg = 'There are no items in the queue';

            $this->logger->info($msg);

            $output->writeln('<fg=green>' . $msg . '</>');

            return 0;
        }

        try {
            /**
             * @psalm-suppress PossiblyNullReference
             * @phpstan-ignore-next-line
             */
            $msg = 'Running ' . $item->queue()->handle() .
                /** @phpstan-ignore-next-line  */
                ' (' . $item->queue()->id() . ') step ' .
                (string) $item->runOrder() . ' (' .
                $item->id() . ')...';

            $this->logger->info($msg);

            $output->writeln('<fg=yellow>' . $msg . '</>');

            /**
             * @psalm-suppress PossiblyNullArgument
             * @phpstan-ignore-next-line
             */
            $this->queueApi->markAsStarted($item->queue());

            $this->queueApi->runItem($item);

            $this->queueApi->queueItemPostRun($item);

            $msg2 = 'Finished';

            $this->logger->info($msg2);

            $output->writeln('<fg=green>' . $msg2 . '</>');

            return 0;
        } catch (Throwable $exception) {
            /**
             * @psalm-suppress PossiblyNullReference
             * @phpstan-ignore-next-line
             */
            $msg = 'An exception was thrown running ' . $item->queue()->handle() .
                /** @phpstan-ignore-next-line  */
                ' (' . $item->queue()->id() . ') step ' .
                (string) $item->runOrder() . ' (' .
                $item->id() . ')...';

            $this->logger->error(
                $msg,
                ['exception' => $exception]
            );

            $output->writeln('<fg=red>' . $msg . '</>');

            /**
             * @psalm-suppress PossiblyNullArgument
             */
            $this->queueApi->markItemStoppedDueToError(
                /** @phpstan-ignore-next-line  */
                $item->queue(),
                $exception
            );

            return 1;
        }
    }
}

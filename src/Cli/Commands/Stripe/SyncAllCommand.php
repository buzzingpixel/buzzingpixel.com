<?php

declare(strict_types=1);

namespace App\Cli\Commands\Stripe;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\QueueApi;
use App\Context\Stripe\LocalStripeApi;
use App\Context\Stripe\QueueActions\SyncAllStripeItemsQueueAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncAllCommand extends Command
{
    public function __construct(
        private QueueApi $queueApi,
        private LocalStripeApi $stripeApi,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('stripe:sync-all');

        $this->setDescription(
            'Sync users, products, and licenses with Stripe'
        );

        $this->addOption(
            'enqueue',
            'e',
            null,
            'Enqueue sync instead of processing inline',
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('enqueue') === true) {
            $output->writeln(
                messages: '<fg=yellow>Enqueuing Stripe sync...</>',
            );

            $this->queueApi->addToQueue(
                queue: (new Queue())
                    ->withHandle('sync-all-stripe-items')
                    ->withAddedQueueItem(
                        newQueueItem: new QueueItem(
                            className: SyncAllStripeItemsQueueAction::class,
                        ),
                    ),
            );

            $output->writeln(
                messages: '<fg=green>Stripe sync added to queue</>',
            );

            return 0;
        }

        $output->writeln(
            messages: '<fg=yellow>Syncing all items with Stripe...</>',
        );

        $this->stripeApi->syncCustomers();
        $this->stripeApi->syncProducts();
        $this->stripeApi->syncLicenses();

        $output->writeln(
            messages: '<fg=green>Stripe sync finished</>',
        );

        return 0;
    }
}

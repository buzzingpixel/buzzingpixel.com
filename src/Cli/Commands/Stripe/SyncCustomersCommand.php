<?php

declare(strict_types=1);

namespace App\Cli\Commands\Stripe;

use App\Context\Stripe\LocalStripeApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncCustomersCommand extends Command
{
    public function __construct(private LocalStripeApi $stripeApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('stripe:sync-customers');

        $this->setDescription(
            'Sync users to Stripe customers'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            messages: '<fg=yellow>Syncing users as customers with Stripe...</>'
        );

        $this->stripeApi->syncCustomers();

        $output->writeln(
            messages: '<fg=green>Users/customers sync finished</>'
        );

        return 0;
    }
}

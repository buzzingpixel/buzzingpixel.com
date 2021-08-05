<?php

namespace App\Cli\Commands\Stripe;

use App\Context\Stripe\LocalStripeApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncLicensesCommand extends Command
{
    public function __construct(private LocalStripeApi $stripeApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('stripe:sync-licenses');

        $this->setDescription(
            'Syncs licenses with stripe'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            messages: '<fg=yellow>Syncing licenses with Stripe...</>'
        );

        $this->stripeApi->syncLicenses();

        $output->writeln(
            messages: '<fg=green>Licenses sync finished</>'
        );

        return 0;
    }
}

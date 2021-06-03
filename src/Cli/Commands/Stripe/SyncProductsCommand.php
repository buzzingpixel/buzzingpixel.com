<?php

declare(strict_types=1);

namespace App\Cli\Commands\Stripe;

use App\Context\Stripe\LocalStripeApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncProductsCommand extends Command
{
    public function __construct(private LocalStripeApi $stripeApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('stripe:sync-products');

        $this->setDescription(
            'Sync software as products with Stripe'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            messages: '<fg=yellow>Syncing software as products with Stripe...</>'
        );

        $this->stripeApi->syncProducts();

        $output->writeln(
            messages: '<fg=green>Software/products sync finished</>'
        );

        return 0;
    }
}

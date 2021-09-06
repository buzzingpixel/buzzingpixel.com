<?php

declare(strict_types=1);

namespace App\Cli\Commands\ImportFromOldSite;

use App\ImportFromOldSite\OrdersAndLicenses\OrdersAndLicenses;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Import002OrdersAndLicensesCommand extends Command
{
    public function __construct(private OrdersAndLicenses $ordersAndLicenses)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('import-from-old-site:002-orders-and-licenses');
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $this->ordersAndLicenses->import();

        return 0;
    }
}

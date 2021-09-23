<?php

declare(strict_types=1);

namespace App\Cli\Commands\Licenses;

use App\Context\Licenses\LicenseApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateMaxVersionOnLicensesCommand extends Command
{
    public function __construct(private LicenseApi $licenseApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('licenses:update-max-version-on-licenses');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            messages: '<fg=yellow>Updating max version on all licenses...</>',
        );

        $this->licenseApi->updateMaxVersionOnLicenses();

        $output->writeln(
            messages: '<fg=green>Max version updated on all licenses</>',
        );

        return 0;
    }
}

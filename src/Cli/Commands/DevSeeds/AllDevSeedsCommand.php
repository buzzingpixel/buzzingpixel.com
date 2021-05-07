<?php

declare(strict_types=1);

namespace App\Cli\Commands\DevSeeds;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AllDevSeedsCommand extends Command
{
    public function __construct(
        private SoftwareDevSeedsCommand $softwareDevSeedsCommand,
        private UsersDevSeedsCommand $usersDevSeedsCommand,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'dev-seeds:all');

        $this->setDescription(description: 'Runs all dev seeders');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->softwareDevSeedsCommand->execute($input, $output);

        $this->usersDevSeedsCommand->execute($input, $output);

        return 0;
    }
}

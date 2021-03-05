<?php

declare(strict_types=1);

namespace App\Cli\Commands\Migrations;

use Config\General;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateStatusCommand extends Command
{
    protected function configure(): void
    {
        $this->setName(name: 'migrate:status');

        $this->setDescription(description: 'Creates a migration');
    }

    public function __construct(
        private PhinxApplication $phinxApplication,
        private General $generalConfig,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->phinxApplication->doRun(
            new ArrayInput([
                0 => 'status',
                '--configuration' => $this->generalConfig->phinxConfigFilePath(),
                '--environment' => 'production',
            ]),
            $output
        );
    }
}

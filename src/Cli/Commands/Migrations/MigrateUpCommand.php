<?php

declare(strict_types=1);

namespace App\Cli\Commands\Migrations;

use Config\General;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateUpCommand extends Command
{
    protected function configure(): void
    {
        $this->setName(name: 'migrate:up');

        $this->setDescription(description: 'Runs all migrations');
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
                0 => 'migrate',
                '--configuration' => $this->generalConfig->phinxConfigFilePath(),
                '--environment' => 'production',
            ]),
            $output
        );
    }
}

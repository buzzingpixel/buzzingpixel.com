<?php

declare(strict_types=1);

namespace App\Cli\Commands\Migrations;

use App\Cli\Services\CliQuestion;
use Config\General;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateDownCommand extends Command
{
    protected function configure(): void
    {
        $this->setName(name: 'migrate:down');

        $this->setDescription(description: 'Rolls back migration(s)');
    }

    public function __construct(
        private CliQuestion $cliQuestion,
        private PhinxApplication $phinxApplication,
        private General $generalConfig,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $params = [
            0 => 'rollback',
            '--configuration' => $this->generalConfig->phinxConfigFilePath(),
            '--environment' => 'production',
        ];

        $target = $this->cliQuestion->ask(
            '<fg=cyan>Specify target (0 to revert all, blank to revert last): </>',
            false
        );

        if ($target !== '') {
            $params['--target'] = $target;
        }

        return $this->phinxApplication->doRun(
            new ArrayInput($params),
            $output
        );
    }
}

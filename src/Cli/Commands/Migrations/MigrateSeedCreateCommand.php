<?php

declare(strict_types=1);

namespace App\Cli\Commands\Migrations;

use App\Cli\Services\CliQuestion;
use App\Utilities\CaseConversion;
use Config\General;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateSeedCreateCommand extends Command
{
    protected function configure(): void
    {
        $this->setName(name: 'migrate:create-seed');

        $this->setDescription(description: 'Creates a seed');
    }

    public function __construct(
        private CliQuestion $cliQuestion,
        private CaseConversion $caseConversion,
        private PhinxApplication $phinxApplication,
        private General $generalConfig,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->phinxApplication->doRun(
            new ArrayInput([
                0 => 'seed:create',
                '--configuration' => $this->generalConfig->phinxConfigFilePath(),
                'name' => $this->caseConversion->stringToPascale(
                    $this->cliQuestion->ask(
                        '<fg=cyan>Provide a seed name: </>'
                    )
                ),
            ]),
            $output
        );
    }
}

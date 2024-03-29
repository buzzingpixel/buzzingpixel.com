<?php

declare(strict_types=1);

namespace App\Cli\Commands\ImportFromOldSite;

use App\ImportFromOldSite\Users\ImportUsers;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Import001UsersCommand extends Command
{
    public function __construct(private ImportUsers $importUsers)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('import-from-old-site:001-users');
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $this->importUsers->import();

        return 0;
    }
}

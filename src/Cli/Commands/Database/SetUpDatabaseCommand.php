<?php

declare(strict_types=1);

namespace App\Cli\Commands\Database;

use Config\Db;
use LogicException;
use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function implode;

class SetUpDatabaseCommand extends Command
{
    public function __construct(private Db $dbConfig)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'database:setup');

        $this->setDescription('Sets up the database if needed');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $dsnArray = [
            'pgsql:',
            'host=',
            $this->dbConfig->dbHost(),
            ';port=',
            (string) $this->dbConfig->dbPort(),
        ];

        $pdo = new PDO(
            implode('', $dsnArray),
            'postgres',
            $this->dbConfig->postgresPassword(),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        $query = $pdo->query(
            'SELECT 1 from pg_database WHERE datname=\'' .
            $this->dbConfig->dbDatabase() .
            '\''
        );

        if ($query === false) {
            throw new LogicException('Unable to query database');
        }

        $check = $query->fetch();

        if ($check !== false) {
            $output->writeln(
                '<fg=green>' . $this->dbConfig->dbDatabase() .
                ' database is already set up</>'
            );

            return 0;
        }

        $output->writeln(
            '<fg=yellow>Creating ' .
            $this->dbConfig->dbDatabase() .
            ' database</>'
        );

        $pdo->exec(
            'CREATE DATABASE ' . $this->dbConfig->dbDatabase()
        );

        $pdo->exec(
            'CREATE USER ' .
            $this->dbConfig->dbUser() .
            " WITH ENCRYPTED PASSWORD '" .
            $this->dbConfig->dbPassword() .
            "';"
        );

        $pdo->exec(
            'GRANT ALL PRIVILEGES ON DATABASE ' .
            $this->dbConfig->dbDatabase() .
            ' TO ' .
            $this->dbConfig->dbUser()
        );

        $output->writeln(
            '<fg=green>' .
            $this->dbConfig->dbDatabase() .
            ' database was created</>'
        );

        return 0;
    }
}

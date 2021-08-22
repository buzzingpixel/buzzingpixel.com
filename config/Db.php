<?php

declare(strict_types=1);

namespace Config;

use function dirname;
use function getenv;
use function is_string;

class Db
{
    private string $entitiesPath;
    private string $postgresPassword = 'root';
    private string $dbHost           = 'buzzingpixel-db';
    private int $dbPort              = 5432;
    private string $dbUser           = 'buzzingpixel';
    private string $dbDatabase       = 'buzzingpixel';
    private string $dbPassword       = 'secret';

    public function __construct()
    {
        $rootPath = dirname(__DIR__);

        $this->entitiesPath = $rootPath . '/src/Persistence/Entities';

        $postgresPassword = getenv('POSTGRES_PASSWORD');
        if (is_string($postgresPassword)) {
            $this->postgresPassword = $postgresPassword;
        }

        $dbHost = getenv('DB_HOST');
        if (is_string($dbHost)) {
            $this->dbHost = $dbHost;
        }

        $dbPort = getenv('DB_PORT');
        if (is_string($dbPort)) {
            $this->dbPort = (int) $dbPort;
        }

        $dbUser = getenv('DB_USER');
        if (is_string($dbUser)) {
            $this->dbUser = $dbUser;
        }

        $dbDatabase = getenv('DB_DATABASE');
        if (is_string($dbDatabase)) {
            $this->dbDatabase = $dbDatabase;
        }

        $dbPassword = getenv('DB_PASSWORD');
        if (! is_string($dbPassword)) {
            return;
        }

        $this->dbPassword = $dbPassword;
    }

    public function entitiesPath(): string
    {
        return $this->entitiesPath;
    }

    public function postgresPassword(): string
    {
        return $this->postgresPassword;
    }

    public function dbHost(): string
    {
        return $this->dbHost;
    }

    public function dbPort(): int
    {
        return $this->dbPort;
    }

    public function dbUser(): string
    {
        return $this->dbUser;
    }

    public function dbDatabase(): string
    {
        return $this->dbDatabase;
    }

    public function dbPassword(): string
    {
        return $this->dbPassword;
    }
}

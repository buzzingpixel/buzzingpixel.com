<?php

declare(strict_types=1);

namespace Config;

use Config\Abstractions\SimpleModel;

use function getenv;
use function is_string;

/**
 * @method bool isInitialized()
 * @method string postgresPassword()
 * @method string dbHost()
 * @method int dbPort()
 * @method string dbUser()
 * @method string dbDatabase()
 * @method string dbPassword()
 */
class Db extends SimpleModel
{
    public function __construct()
    {
        $postgresPassword = getenv('POSTGRES_PASSWORD');
        if (is_string($postgresPassword)) {
            static::$postgresPassword = $postgresPassword;
        }

        $dbHost = getenv('DB_HOST');
        if (is_string($dbHost)) {
            static::$dbHost = $dbHost;
        }

        $dbPort = getenv('DB_PORT');
        if (is_string($dbPort)) {
            static::$dbPort = (int) $dbPort;
        }

        $dbUser = getenv('DB_USER');
        if (is_string($dbUser)) {
            static::$dbUser = $dbUser;
        }

        $dbDatabase = getenv('DB_DATABASE');
        if (is_string($dbDatabase)) {
            static::$dbDatabase = $dbDatabase;
        }

        $dbPassword = getenv('DB_PASSWORD');
        if (is_string($dbPassword)) {
            static::$dbPassword = $dbPassword;
        }

        self::$isInitialized = true;
    }

    public static bool $isInitialized = false;

    public static string $postgresPassword = 'root';

    public static string $dbHost = 'buzzingpixel-db';

    public static int $dbPort = 5432;

    public static string $dbUser = 'buzzingpixel';

    public static string $dbDatabase = 'buzzingpixel';

    public static string $dbPassword = 'secret';
}

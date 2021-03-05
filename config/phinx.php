<?php

declare(strict_types=1);

use App\Globals;
use Config\Db;

$dbConfig = Globals::di()->get(Db::class);

assert($dbConfig instanceof Db);

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/Data/Migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/Data/Seeds',
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database' => 'production',
        'production' => [
            'adapter' => 'pgsql',
            'host' => $dbConfig->dbHost(),
            'name' => $dbConfig->dbDatabase(),
            'user' => $dbConfig->dbUser(),
            'pass' => $dbConfig->dbPassword(),
            'port' => $dbConfig->dbPort(),
        ],
    ],
    'version_order' => 'creation',
];

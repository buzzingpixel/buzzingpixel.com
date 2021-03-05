<?php

declare(strict_types=1);

use Config\Db;
use Config\General;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;

return [
    EntityManager::class => static function (ContainerInterface $di): EntityManager {
        $dbConfig = $di->get(Db::class);

        assert($dbConfig instanceof Db);

        $generalConfig = $di->get(General::class);

        assert($generalConfig instanceof General);

        return EntityManager::create(
            connection: [
                'driver' => 'pdo_mysql',
                'user' => $dbConfig->dbUser(),
                'password' => $dbConfig->dbPassword(),
                'dbname' => $dbConfig->dbDatabase(),
            ],
            config: Setup::createAnnotationMetadataConfiguration(
                paths: [$generalConfig->rootPath() . '/src/Persistence'],
                isDevMode: (bool) getenv('DEV_MODE'),
                proxyDir: $generalConfig->pathToStorageDirectory() . '/doctrine/proxy',
                cache: new PhpFileCache(
                    directory: $generalConfig->pathToStorageDirectory() . '/doctrine/cache',
                ),
                useSimpleAnnotationReader: false,
            ),
        );
    },
];

<?php

declare(strict_types=1);

use Config\Db;
use Config\General;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Doctrine\UuidType;

return [
    EntityManager::class => static function (ContainerInterface $di): EntityManager {
        Type::addType('uuid', UuidType::class);

        $dbConfig = $di->get(Db::class);

        assert($dbConfig instanceof Db);

        $generalConfig = $di->get(General::class);

        assert($generalConfig instanceof General);

        return EntityManager::create(
            connection: [
                'driver' => 'pdo_pgsql',
                'user' => $dbConfig->dbUser(),
                'password' => $dbConfig->dbPassword(),
                'host' => $dbConfig->dbHost(),
                'port' => $dbConfig->dbPort(),
                'dbname' => $dbConfig->dbDatabase(),
                'charset'  => 'utf8',
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

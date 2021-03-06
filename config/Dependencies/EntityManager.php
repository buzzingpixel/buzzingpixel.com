<?php

declare(strict_types=1);

use App\Persistence\Types\UtcDateTimeImmutableType;
use App\Persistence\Types\UtcDateTimeType;
use App\Persistence\Types\UtcDateTimeTzImmutableType;
use App\Persistence\Types\UtcDateTimeTzType;
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
        Type::addType(name: 'uuid', className:  UuidType::class);

        Type::overrideType(
            name: 'datetime',
            className: UtcDateTimeType::class
        );

        Type::overrideType(
            name: 'datetimetz',
            className: UtcDateTimeTzType::class
        );

        Type::overrideType(
            name: 'datetime_immutable',
            className: UtcDateTimeImmutableType::class
        );

        Type::overrideType(
            name: 'datetimetz_immutable',
            className: UtcDateTimeTzImmutableType::class
        );

        $dbConfig = $di->get(Db::class);

        assert($dbConfig instanceof Db);

        $generalConfig = $di->get(General::class);

        assert($generalConfig instanceof General);

        $storageDir = $generalConfig->pathToStorageDirectory();

        $proxyDir = $generalConfig->devMode() ?
            null :
            $storageDir . '/doctrine/proxy';

        $cache = $generalConfig->devMode() ?
            null :
            new PhpFileCache(
                directory: $storageDir . '/doctrine/cache',
            );

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
                paths: [$generalConfig->rootPath() . '/src/Persistence/Entities'],
                isDevMode: $generalConfig->devMode(),
                proxyDir: $proxyDir,
                cache: $cache,
                useSimpleAnnotationReader: false,
            ),
        );
    },
];

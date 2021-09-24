<?php

declare(strict_types=1);

use App\Persistence\Types\UtcDateTimeImmutableType;
use App\Persistence\Types\UtcDateTimeType;
use App\Persistence\Types\UtcDateTimeTzImmutableType;
use App\Persistence\Types\UtcDateTimeTzType;
use Config\Db;
use Config\General;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Doctrine\UuidType;

return [
    EntityManager::class => static function (ContainerInterface $di): EntityManager {
        Type::addType('uuid', UuidType::class);

        Type::overrideType(
            'datetime',
            UtcDateTimeType::class
        );

        Type::overrideType(
            'datetimetz',
            UtcDateTimeTzType::class
        );

        Type::overrideType(
            'datetime_immutable',
            UtcDateTimeImmutableType::class
        );

        Type::overrideType(
            'datetimetz_immutable',
            UtcDateTimeTzImmutableType::class
        );

        $dbConfig = $di->get(Db::class);

        assert($dbConfig instanceof Db);

        $generalConfig = $di->get(General::class);

        assert($generalConfig instanceof General);

        $storageDir = $generalConfig->pathToStorageDirectory();

        $proxyDir = $storageDir . '/doctrine/proxy';

        $devMode = $generalConfig->devMode();

        if ($devMode) {
            $cache = new ArrayCache();
        } else {
            $cache = new RedisCache();

            $redis = $di->get(Redis::class);

            assert($redis instanceof Redis);

            $cache->setRedis($redis);
        }

        return EntityManager::create(
            [
                'driver' => 'pdo_pgsql',
                'user' => $dbConfig->dbUser(),
                'password' => $dbConfig->dbPassword(),
                'host' => $dbConfig->dbHost(),
                'port' => $dbConfig->dbPort(),
                'dbname' => $dbConfig->dbDatabase(),
                'charset'  => 'utf8',
            ],
            Setup::createAnnotationMetadataConfiguration(
                [$dbConfig->entitiesPath()],
                $devMode,
                $proxyDir,
                $cache,
                false,
            ),
        );
    },
];

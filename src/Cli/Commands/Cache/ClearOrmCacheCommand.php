<?php

declare(strict_types=1);

namespace App\Cli\Commands\Cache;

use Config\General;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\XcacheCache;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function exec;

class ClearOrmCacheCommand extends Command
{
    public function __construct(
        private General $config,
        private EntityManager $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'cache:clear-orm');

        $this->setDescription(description: 'Clears the ORM cache');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(messages: '<fg=yellow>Clearing ORM cache...</>');

        $storagePath = $this->config->pathToStorageDirectory();

        $ormCachePath = $storagePath . '/doctrine/*';

        exec(command: 'rm -rf ' . $ormCachePath);

        /**
         * Clear entity manager metadata cache
         *
         * @see \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand
         * ^ The damn thing is so locked down we can't use this command from the
         * outside at all
         */
        try {
            $metadataCacheDriver = $this->entityManager
                ->getConfiguration()
                ->getMetadataCacheImpl();

            $instanceOfApcCache = $metadataCacheDriver instanceof ApcCache;

            $instanceOfXCache = $metadataCacheDriver instanceof XcacheCache;

            if (
                $metadataCacheDriver !== null &&
                ! $instanceOfApcCache &&
                ! $instanceOfXCache
            ) {
                /**
                 * @psalm-suppress UndefinedInterfaceMethod
                 * @phpstan-ignore-next-line
                 */
                $metadataCacheDriver->deleteAll();

                /**
                 * @psalm-suppress UndefinedInterfaceMethod
                 * @phpstan-ignore-next-line
                 */
                $metadataCacheDriver->flushAll();
            }
        } catch (Throwable $e) {
        }

        /**
         * Clear entity manager query cache
         *
         * @see \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand
         * ^ The damn thing is so locked down we can't use this command from the
         * outside at all
         */
        try {
            $queryCacheDriver = $this->entityManager
                ->getConfiguration()
                ->getMetadataCacheImpl();

            $instanceOfApcCache = $queryCacheDriver instanceof ApcCache;

            $instanceOfXCache = $queryCacheDriver instanceof XcacheCache;

            if (
                $queryCacheDriver !== null &&
                ! $instanceOfApcCache &&
                ! $instanceOfXCache
            ) {
                /**
                 * @psalm-suppress UndefinedInterfaceMethod
                 * @phpstan-ignore-next-line
                 */
                $queryCacheDriver->deleteAll();

                /**
                 * @psalm-suppress UndefinedInterfaceMethod
                 * @phpstan-ignore-next-line
                 */
                $queryCacheDriver->flushAll();
            }
        } catch (Throwable $e) {
        }

        /**
         * Clear entity manager query cache
         *
         * @see \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand
         * ^ The damn thing is so locked down we can't use this command from the
         * outside at all
         */
        try {
            $resultCacheDriver = $this->entityManager
                ->getConfiguration()
                ->getMetadataCacheImpl();

            $instanceOfApcCache = $resultCacheDriver instanceof ApcCache;

            $instanceOfXCache = $resultCacheDriver instanceof XcacheCache;

            if (
                $resultCacheDriver !== null &&
                ! $instanceOfApcCache &&
                ! $instanceOfXCache
            ) {
                /**
                 * @psalm-suppress UndefinedInterfaceMethod
                 * @phpstan-ignore-next-line
                 */
                $resultCacheDriver->deleteAll();

                /**
                 * @psalm-suppress UndefinedInterfaceMethod
                 * @phpstan-ignore-next-line
                 */
                $resultCacheDriver->flushAll();
            }
        } catch (Throwable $e) {
        }

        $output->writeln(messages: '<fg=green>ORM cache cleared</>');

        return 0;
    }
}

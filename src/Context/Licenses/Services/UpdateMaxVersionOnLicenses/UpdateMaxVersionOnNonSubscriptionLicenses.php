<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\UpdateMaxVersionOnLicenses;

use App\Persistence\Entities\Licenses\LicenseRecord;
use App\Persistence\Entities\Software\SoftwareVersionRecord;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\Mapping\MappingException;

use function assert;

class UpdateMaxVersionOnNonSubscriptionLicenses
{
    public function __construct(
        private EntityManager $entityManager,
    ) {
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MappingException
     */
    public function update(): void
    {
        $records = $this->entityManager
            ->getRepository(LicenseRecord::class)
            ->createQueryBuilder('l')
            ->where('l.stripeSubscriptionAmount < 1')
            ->getQuery()
            ->toIterable();

        $batchSize = 20;

        $i = 1;

        $total = 0;

        foreach ($records as $record) {
            assert($record instanceof LicenseRecord);
            $total++;

            $version = $record->getSoftware()
                ->getVersions()
                ->filter(static fn (
                    SoftwareVersionRecord $r
                ) => $r->getMajorVersion() === $record->getMajorVersion())
                ->first();

            $isInstance = $version instanceof SoftwareVersionRecord;

            if (! $isInstance) {
                continue;
            }

            assert($version instanceof SoftwareVersionRecord);

            $record->setMajorVersion(majorVersion: $version->getMajorVersion());

            $record->setMaxVersion(maxVersion: $version->getVersion());

            $this->entityManager->persist($record);

            if ($i % $batchSize === 0) {
                $this->entityManager->flush(); // Executes all updates.
                $this->entityManager->clear(); // Detaches all objects from Doctrine
            }

            ++$i;
        }

        $this->entityManager->flush();
    }
}

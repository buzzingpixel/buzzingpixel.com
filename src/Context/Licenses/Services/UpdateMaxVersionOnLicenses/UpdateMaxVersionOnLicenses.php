<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\UpdateMaxVersionOnLicenses;

use App\Persistence\Entities\Licenses\LicenseRecord;
use App\Persistence\Entities\Software\SoftwareVersionRecord;
use Doctrine\ORM\EntityManager;

use function assert;

class UpdateMaxVersionOnLicenses
{
    public function __construct(
        private EntityManager $entityManager,
    ) {
    }

    public function update(): void
    {
        $records = $this->entityManager
            ->getRepository(LicenseRecord::class)
            ->createQueryBuilder('l')
            ->where('l.stripeSubscriptionAmount > 0')
            ->andWhere('l.stripeStatus = :status')
            ->setParameter('status', 'active')
            ->getQuery()
            ->toIterable();

        $batchSize = 20;

        $i = 1;

        $total = 0;

        foreach ($records as $record) {
            assert($record instanceof LicenseRecord);
            $total++;

            $version = $record->getSoftware()->getVersions()->first();

            assert($version instanceof SoftwareVersionRecord);

            $record->setMaxVersion($version->getVersion());

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

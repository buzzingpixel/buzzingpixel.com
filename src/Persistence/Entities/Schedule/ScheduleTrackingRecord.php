<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Schedule;

use App\Context\Schedule\Entities\ScheduleItem;
use App\Persistence\PropertyTraits\ClassName;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\IsRunning;
use App\Persistence\PropertyTraits\LastRunEndAt;
use App\Persistence\PropertyTraits\LastRunStartAt;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="schedule_tracking")
 */
class ScheduleTrackingRecord
{
    use Id;
    use ClassName;
    use IsRunning;
    use LastRunStartAt;
    use LastRunEndAt;

    public function hydrateFromEntity(ScheduleItem $entity): void
    {
        $this->setId(Uuid::fromString($entity->id()));
        $this->setClassName($entity->className());
        $this->setIsRunning($entity->isRunning());
        $this->setLastRunStartAt($entity->lastRunStartAt());
        $this->setLastRunEndAt($entity->lastRunEndAt());
    }
}

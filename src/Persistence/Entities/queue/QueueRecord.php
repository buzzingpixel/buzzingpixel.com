<?php

declare(strict_types=1);

namespace App\Persistence\Entities\queue;

use App\Persistence\PropertyTraits\AddedAt;
use App\Persistence\PropertyTraits\AssumeDeadAfter;
use App\Persistence\PropertyTraits\FinishedAt;
use App\Persistence\PropertyTraits\FinishedDueToError;
use App\Persistence\PropertyTraits\Handle;
use App\Persistence\PropertyTraits\HasStarted;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\InitialAssumeDeadAfter;
use App\Persistence\PropertyTraits\IsFinished;
use App\Persistence\PropertyTraits\IsRunning;
use App\Persistence\PropertyTraits\PercentComplete;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="queue")
 */
class QueueRecord
{
    use Id;
    use Handle;
    use HasStarted;
    use IsRunning;
    use AssumeDeadAfter;
    use InitialAssumeDeadAfter;
    use IsFinished;
    use FinishedDueToError;
    use PercentComplete;
    use AddedAt;
    use FinishedAt;
}

<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Cache;

use App\Persistence\PropertyTraits\ExpiresAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\Key;
use App\Persistence\PropertyTraits\Value;
use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="cache_pool")
 */
class CachePoolItemRecord
{
    use Id;
    use Key;
    use Value;
    use ExpiresAt;
}

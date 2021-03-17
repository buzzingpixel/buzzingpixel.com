<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Cache;

use App\Context\DatabaseCache\Entities\DatabaseCacheItem;
use App\Persistence\PropertyTraits\ExpiresAt;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\Key;
use App\Persistence\PropertyTraits\Value;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

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

    public function hydrateFromEntity(DatabaseCacheItem $entity): void
    {
        $this->setId(Uuid::fromString($entity->id()));
        $this->setKey($entity->getKey());
        $this->setValue($entity->get());
        $this->setExpiresAt($entity->getExpiresAt());
    }
}

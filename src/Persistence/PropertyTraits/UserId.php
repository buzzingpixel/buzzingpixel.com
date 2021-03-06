<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;
use Ramsey\Uuid\UuidInterface;

trait UserId
{
    /**
     * @Mapping\Id
     * @Mapping\Column(
     *     name="user_id",
     *     type="uuid",
     * )
     */
    protected UuidInterface $userId;

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function setUserId(UuidInterface $userId): void
    {
        $this->userId = $userId;
    }
}

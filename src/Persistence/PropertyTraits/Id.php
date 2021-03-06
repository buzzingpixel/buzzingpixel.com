<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;
use Ramsey\Uuid\UuidInterface;

trait Id
{
    /**
     * @Mapping\Id
     * @Mapping\Column(
     *     name="id",
     *     type="uuid",
     *     unique=true,
     * )
     */
    protected UuidInterface $id;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }
}

<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;
use Ramsey\Uuid\UuidInterface;

trait CookieId
{
    /**
     * @Mapping\Column(
     *     name="cookie_id",
     *     type="uuid",
     * )
     */
    protected UuidInterface $cookieId;

    public function getCookieId(): UuidInterface
    {
        return $this->cookieId;
    }

    public function setCookieId(UuidInterface $cookieId): void
    {
        $this->cookieId = $cookieId;
    }
}

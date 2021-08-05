<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping;

trait StripeCanceledAt
{
    /**
     * @Mapping\Column(
     *     name="stripe_canceled_at",
     *     type="datetimetz_immutable",
     *     nullable=true
     * )
     */
    protected ?DateTimeImmutable $stripeCanceledAt;

    public function getStripeCanceledAt(): ?DateTimeImmutable
    {
        return $this->stripeCanceledAt;
    }

    public function setStripeCanceledAt(?DateTimeImmutable $stripeCanceledAt): void
    {
        $this->stripeCanceledAt = $stripeCanceledAt;
    }
}

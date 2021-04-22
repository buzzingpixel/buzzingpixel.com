<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripeCaptured
{
    /**
     * @Mapping\Column(
     *     name="stripe_captured",
     *     type="boolean"
     * )
     */
    protected bool $stripeCaptured = true;

    public function getStripeCaptured(): bool
    {
        return $this->stripeCaptured;
    }

    public function setStripeCaptured(bool $stripeCaptured): void
    {
        $this->stripeCaptured = $stripeCaptured;
    }
}

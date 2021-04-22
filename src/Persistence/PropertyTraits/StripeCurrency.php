<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait StripeCurrency
{
    /**
     * @Mapping\Column(
     *     name="stripe_currency",
     *     type="string",
     * )
     */
    protected string $stripeCurrency = '';

    public function getStripeCurrency(): string
    {
        return $this->stripeCurrency;
    }

    public function setStripeCurrency(string $stripeCurrency): void
    {
        $this->stripeCurrency = $stripeCurrency;
    }
}

<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Timezone
{
    /**
     * @Mapping\Column(
     *     name="timezone",
     *     type="string"
     * )
     */
    protected string $timezone = '';

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): void
    {
        $this->timezone = $timezone;
    }
}

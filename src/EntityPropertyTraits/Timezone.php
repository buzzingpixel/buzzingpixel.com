<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

use DateTimeZone;

trait Timezone
{
    private DateTimeZone $timezone;

    public function timezone(): DateTimeZone
    {
        return $this->timezone;
    }

    /**
     * @return $this
     */
    public function withTimezone(string | DateTimeZone $timezone): self
    {
        $clone = clone $this;

        if ($timezone instanceof DateTimeZone) {
            $clone->timezone = $timezone;
        } else {
            $clone->timezone = new DateTimeZone($timezone);
        }

        return $clone;
    }
}

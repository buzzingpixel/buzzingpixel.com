<?php

declare(strict_types=1);

namespace App\EntityValueObjects;

use App\Context\Software\Entities\Software;
use Stringable;

class SoftwareNotNull implements Stringable
{
    public function __construct(private Software $software)
    {
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->software->id();
    }

    public function getSoftware(): Software
    {
        return $this->software;
    }
}

<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services;

use Ramsey\Uuid\UuidFactory;

class GenerateLicenseKey
{
    public function __construct(private UuidFactory $uuidFactory)
    {
    }

    public function generate(): string
    {
        return $this->uuidFactory->uuid4()->toString();
    }
}

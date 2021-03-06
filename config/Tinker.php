<?php

declare(strict_types=1);

namespace Config;

use function dd;

class Tinker
{
    public function __invoke(): void
    {
        dd('here');
    }
}

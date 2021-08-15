<?php

declare(strict_types=1);

namespace App\Context\Path;

use App\Context\Path\Entities\Path;
use Config\General;

class PathApi
{
    public function __construct(
        private General $config,
    ) {
    }

    public function pathFromProjectRoot(string $path = ''): Path
    {
        return new Path($this->config->rootPath() . '/' . $path);
    }
}

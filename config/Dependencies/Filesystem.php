<?php

declare(strict_types=1);

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

use function DI\autowire;
use function DI\get;

return [
    Filesystem::class => autowire(Filesystem::class)
        ->constructorParameter(
            'adapter',
            get(LocalFilesystemAdapter::class),
        ),
    LocalFilesystemAdapter::class => autowire(LocalFilesystemAdapter::class)
        ->constructorParameter(
            'location',
            '/',
        ),
];

<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Construct;

class ConstructVariables
{
    public const NAV = [
        [
            'uri' => '/software/construct',
            'content' => 'Features',
        ],
        [
            'uri' => '/software/construct/documentation',
            'content' => 'Documentation',
        ],
        [
            'uri' => self::CHANGELOG_BASE_URI,
            'content' => 'Changelog',
        ],
    ];

    public const CHANGELOG_BASE_URI = '/software/construct/changelog';
}

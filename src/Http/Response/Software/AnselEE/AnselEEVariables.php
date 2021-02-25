<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselEE;

class AnselEEVariables
{
    public const NAV = [
        [
            'uri' => '/software/ansel-ee',
            'content' => 'Features',
        ],
        [
            'uri' => '/software/ansel-ee/documentation',
            'content' => 'Documentation',
        ],
        [
            'uri' => self::CHANGELOG_BASE_URI,
            'content' => 'Changelog',
        ],
    ];

    public const CHANGELOG_BASE_URI = '/software/ansel-ee/changelog';
}

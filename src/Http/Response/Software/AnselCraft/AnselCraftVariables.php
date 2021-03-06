<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselCraft;

class AnselCraftVariables
{
    public const NAV = [
        [
            'uri' => '/software/ansel-craft',
            'content' => 'Features',
        ],
        [
            'uri' => '/software/ansel-craft/documentation',
            'content' => 'Documentation',
        ],
        [
            'uri' => self::CHANGELOG_BASE_URI,
            'content' => 'Changelog',
        ],
    ];

    public const CHANGELOG_BASE_URI = '/software/ansel-craft/changelog';
}

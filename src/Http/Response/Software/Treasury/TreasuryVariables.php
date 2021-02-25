<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Treasury;

class TreasuryVariables
{
    public const NAV = [
        [
            'uri' => '/software/treasury',
            'content' => 'Features',
        ],
        [
            'uri' => '/software/treasury/documentation',
            'content' => 'Documentation',
        ],
        [
            'uri' => self::CHANGELOG_BASE_URI,
            'content' => 'Changelog',
        ],
    ];

    public const CHANGELOG_BASE_URI = '/software/treasury/changelog';
}

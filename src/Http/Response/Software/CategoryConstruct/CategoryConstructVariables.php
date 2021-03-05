<?php

declare(strict_types=1);

namespace App\Http\Response\Software\CategoryConstruct;

class CategoryConstructVariables
{
    public const NAV = [
        [
            'uri' => '/software/category-construct',
            'content' => 'Features',
        ],
        [
            'uri' => '/software/category-construct/documentation',
            'content' => 'Documentation',
        ],
        [
            'uri' => self::CHANGELOG_BASE_URI,
            'content' => 'Changelog',
        ],
    ];

    public const CHANGELOG_BASE_URI = '/software/category-construct/changelog';
}

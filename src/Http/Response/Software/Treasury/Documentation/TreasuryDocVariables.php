<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Treasury\Documentation;

use LogicException;

class TreasuryDocVariables
{
    public const V1_BREADCRUMB_TRAIL = [
        [
            'content' => 'Software',
            'uri' => '/software',
        ],
        [
            'content' => 'Treasury',
            'uri' => '/software/treasury',
        ],
        ['content' => 'Documentation'],
    ];

    public const VERSION_NAV = [];

    /**
     * @return mixed[]
     */
    // public static function getVersionNav(?string $activeHandle = null): array
    // {
    //     $versionNav = self::VERSION_NAV;
    //
    //     if ($activeHandle !== null) {
    //         if (! isset($versionNav[$activeHandle])) {
    //             throw new LogicException(
    //                 'Version nav handle ' . $activeHandle . ' does not exist'
    //             );
    //         }
    //
    //         $versionNav[$activeHandle]['isActive'] = true;
    //     }
    //
    //     return $versionNav;
    // }

    public const VERSION_2_PAGES = [
        'getting-started' => [
            'content' => 'Getting Started',
            'uri' => '/software/treasury/documentation',
            'isActive' => false,
        ],
        'locations' => [
            'content' => 'Locations',
            'uri' => '/software/treasury/documentation/locations',
            'isActive' => false,
        ],
        'template-tags' => [
            'content' => 'Template Tags',
            'uri' => '/software/treasury/documentation/template-tags',
            'isActive' => false,
        ],
        'developers' => [
            'content' => 'Developers',
            'uri' => '/software/treasury/documentation/developers',
            'isActive' => false,
        ],
    ];

    /**
     * @return mixed[]
     */
    public static function getVersion1Pages(?string $activeHandle = null): array
    {
        $pages = self::VERSION_2_PAGES;

        if ($activeHandle !== null) {
            if (! isset($pages[$activeHandle])) {
                throw new LogicException(
                    'Version 1 pages handle ' . $activeHandle . ' does not exist'
                );
            }

            $pages[$activeHandle]['isActive'] = true;
        }

        return $pages;
    }
}

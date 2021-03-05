<?php

declare(strict_types=1);

namespace App\Http\Response\Software\CategoryConstruct\Documentation;

use LogicException;

class CategoryConstructDocVariables
{
    public const V2_BREADCRUMB_TRAIL = [
        [
            'content' => 'Software',
            'uri' => '/software',
        ],
        [
            'content' => 'Category Construct',
            'uri' => '/software/category-construct',
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
            'uri' => '/software/category-construct/documentation',
            'isActive' => false,
        ],
        'template-tags' => [
            'content' => 'Template Tags',
            'uri' => '/software/category-construct/documentation/template-tags',
            'isActive' => false,
        ],
    ];

    /**
     * @return mixed[]
     */
    public static function getVersion2Pages(?string $activeHandle = null): array
    {
        $pages = self::VERSION_2_PAGES;

        if ($activeHandle !== null) {
            if (! isset($pages[$activeHandle])) {
                throw new LogicException(
                    'Version 2 pages handle ' . $activeHandle . ' does not exist'
                );
            }

            $pages[$activeHandle]['isActive'] = true;
        }

        return $pages;
    }
}

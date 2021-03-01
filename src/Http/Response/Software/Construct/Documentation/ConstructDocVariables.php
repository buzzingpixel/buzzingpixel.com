<?php

declare(strict_types=1);

namespace App\Http\Response\Software\Construct\Documentation;

use LogicException;

class ConstructDocVariables
{
    public const V1_BREADCRUMB_TRAIL = [
        [
            'content' => 'Software',
            'uri' => '/software',
        ],
        [
            'content' => 'Construct',
            'uri' => '/software/construct',
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
            'uri' => '/software/construct/documentation',
            'isActive' => false,
        ],
        'control-panel' => [
            'content' => 'Control Panel',
            'uri' => '/software/construct/documentation/control-panel',
            'isActive' => false,
        ],
        'field-types' => [
            'content' => 'Field Types',
            'uri' => '/software/construct/documentation/field-types',
            'isActive' => false,
        ],
        'routing' => [
            'content' => 'Routing',
            'uri' => '/software/construct/documentation/routing',
            'isActive' => false,
        ],
        'config-routing' => [
            'content' => 'Config Routing',
            'uri' => '/software/construct/documentation/config-routing',
            'isActive' => false,
        ],
        'template-tags' => [
            'content' => 'Template Tags',
            'uri' => '/software/construct/documentation/template-tags',
            'isActive' => false,
        ],
        'extension-hooks' => [
            'content' => 'Extension Hooks',
            'uri' => '/software/construct/documentation/extension-hooks',
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

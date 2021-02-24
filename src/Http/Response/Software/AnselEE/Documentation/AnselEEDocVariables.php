<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselEE\Documentation;

use LogicException;

class AnselEEDocVariables
{
    public const V2_BREADCRUMB_TRAIL = [
        [
            'content' => 'Software',
            'uri' => '/software',
        ],
        [
            'content' => 'Ansel for EE',
            'uri' => '/software/ansel-ee',
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
            'uri' => '/software/ansel-ee/documentation',
            'isActive' => false,
        ],
        'field-type-settings' => [
            'content' => 'Field Type Settings',
            'uri' => '/software/ansel-ee/documentation/field-type-settings',
            'isActive' => false,
        ],
        'field-type-use' => [
            'content' => 'Field Type Usage',
            'uri' => '/software/ansel-ee/documentation/field-type-use',
            'isActive' => false,
        ],
        'templating' => [
            'content' => 'Templating',
            'uri' => '/software/ansel-ee/documentation/templating',
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

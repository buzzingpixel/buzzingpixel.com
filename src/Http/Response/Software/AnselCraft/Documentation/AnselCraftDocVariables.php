<?php

declare(strict_types=1);

namespace App\Http\Response\Software\AnselCraft\Documentation;

use LogicException;

class AnselCraftDocVariables
{
    public const V1_BREADCRUMB_TRAIL = [
        [
            'content' => 'Software',
            'uri' => '/software',
        ],
        [
            'content' => 'Ansel for Craft',
            'uri' => '/software/ansel-craft',
        ],
        ['content' => 'Documentation (V1 Legacy)'],
    ];
    public const V2_BREADCRUMB_TRAIL = [
        [
            'content' => 'Software',
            'uri' => '/software',
        ],
        [
            'content' => 'Ansel for Craft',
            'uri' => '/software/ansel-craft',
        ],
        ['content' => 'Documentation'],
    ];

    public const VERSION_NAV = [
        'ansel2' => [
            'content' => 'Ansel 2.x (current)',
            'uri' => '/software/ansel-craft/documentation',
            'isActive' => false,
        ],
        'ansel1' => [
            'content' => 'Ansel 1.x (legacy)',
            'uri' => '/software/ansel-craft/documentation/v1',
            'isActive' => false,
        ],
    ];

    /**
     * @return mixed[]
     */
    public static function getVersionNav(?string $activeHandle = null): array
    {
        $versionNav = self::VERSION_NAV;

        if ($activeHandle !== null) {
            if (! isset($versionNav[$activeHandle])) {
                throw new LogicException(
                    'Version nav handle ' . $activeHandle . ' does not exist'
                );
            }

            $versionNav[$activeHandle]['isActive'] = true;
        }

        return $versionNav;
    }

    public const VERSION_1_PAGES = [
        'getting-started' => [
            'content' => 'Getting Started',
            'uri' => '/software/ansel-craft/documentation/v1',
            'isActive' => false,
        ],
        'field-type-settings' => [
            'content' => 'Field Type Settings',
            'uri' => '/software/ansel-craft/documentation/v1/field-type-settings',
            'isActive' => false,
        ],
        'field-type-use' => [
            'content' => 'Field Type Use',
            'uri' => '/software/ansel-craft/documentation/v1/field-type-use',
            'isActive' => false,
        ],
        'templating' => [
            'content' => 'Templating',
            'uri' => '/software/ansel-craft/documentation/v1/templating',
            'isActive' => false,
        ],
    ];

    /**
     * @return mixed[]
     */
    public static function getVersion1Pages(?string $activeHandle = null): array
    {
        $pages = self::VERSION_1_PAGES;

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

    public const VERSION_2_PAGES = [
        'getting-started' => [
            'content' => 'Getting Started',
            'uri' => '/software/ansel-craft/documentation',
            'isActive' => false,
        ],
        'field-type-settings' => [
            'content' => 'Field Type Settings',
            'uri' => '/software/ansel-craft/documentation/field-type-settings',
            'isActive' => false,
        ],
        'field-type-use' => [
            'content' => 'Field Type Use',
            'uri' => '/software/ansel-craft/documentation/field-type-use',
            'isActive' => false,
        ],
        'templating' => [
            'content' => 'Templating',
            'uri' => '/software/ansel-craft/documentation/templating',
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

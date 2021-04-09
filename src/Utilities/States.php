<?php

declare(strict_types=1);

namespace App\Utilities;

use Cdtweb\UsStatesList;
use Exception;

class States
{
    /**
     * @return mixed[]
     *
     * @throws Exception
     */
    public static function statesSelectList(
        string $emptyOptionLabel = 'Select state&hellip;',
    ): array {
        $options = [
            [
                'value' => '',
                'label' => $emptyOptionLabel,
            ],
        ];

        /** @psalm-suppress MixedAssignment */
        foreach (UsStatesList::all() as $stateAbbr => $state) {
            $options[] = [
                'value' => $stateAbbr,
                'label' => $state,
            ];
        }

        return $options;
    }
}

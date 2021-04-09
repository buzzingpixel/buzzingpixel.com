<?php

declare(strict_types=1);

namespace App\Utilities;

use Exception;
use League\ISO3166\ISO3166 as ISO3166Source;

class Countries
{
    /**
     * @return mixed[]
     *
     * @throws Exception
     */
    public static function countrySelectList(
        string $emptyOptionLabel = 'Select Country&hellip;'
    ): array {
        $usa = (new ISO3166Source())->alpha2('US');

        $all = (new ISO3166Source())->all();

        $options = [
            [
                'value' => '',
                'label' => $emptyOptionLabel,
            ],
            [
                'value' => $usa['alpha3'],
                'label' => $usa['name'],
            ],
        ];

        foreach ($all as $country) {
            $options[] = [
                'value' => $country['alpha3'],
                'label' => $country['name'],
            ];
        }

        return $options;
    }
}

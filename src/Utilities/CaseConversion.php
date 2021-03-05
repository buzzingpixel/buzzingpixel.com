<?php

declare(strict_types=1);

namespace App\Utilities;

use Safe\Exceptions\PcreException;

use function explode;
use function lcfirst;
use function Safe\preg_split;
use function ucfirst;

class CaseConversion
{
    public function stringToPascale(string $str): string
    {
        return $this->spaceConvert($this->underscoreConvert($str));
    }

    public function stringToCamel(string $str): string
    {
        return lcfirst($this->stringToPascale($str));
    }

    private function underscoreConvert(string $str): string
    {
        $finalStr = '';
        foreach (explode('_', $str) as $item) {
            $finalStr .= ucfirst($item);
        }

        return $finalStr;
    }

    private function spaceConvert(string $str): string
    {
        $finalStr = '';

        try {
            /** @var array<int, string> $stringArray */
            $stringArray = preg_split('/\s+/', $str);

            foreach ($stringArray as $item) {
                $finalStr .= ucfirst($item);
            }
        } catch (PcreException $e) {
        }

        return $finalStr;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Utilities\General;

class PageNumberUtil
{
    public function pageNumberFromString(string $incoming): int
    {
        $pageNum = (int) $incoming;

        return $pageNum < 1 ? 1 : $pageNum;
    }

    public function pageNumberOrNullFromString(string $incoming): ?int
    {
        if ($incoming === '') {
            return 1;
        }

        $pageNum = (int) $incoming;

        return $pageNum < 2 ? null : $pageNum;
    }
}

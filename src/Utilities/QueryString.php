<?php

declare(strict_types=1);

namespace App\Utilities;

use function http_build_query;
use function parse_str;

class QueryString
{
    /**
     * @return mixed[]
     */
    public static function parse(string $str): array
    {
        $query = [];

        parse_str($str, $query);

        return $query;
    }

    /**
     * @param mixed[] $query
     */
    public static function build(array $query): string
    {
        return http_build_query($query);
    }
}

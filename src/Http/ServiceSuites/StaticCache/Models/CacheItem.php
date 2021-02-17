<?php

declare(strict_types=1);

namespace App\Http\ServiceSuites\StaticCache\Models;

class CacheItem
{
    public int $statusCode = 200;

    public string $reasonPhrase = 'OK';

    public string $protocolVersion = '1.1';

    /** @var mixed[] */
    public array $headers = [];

    public string $body = '';
}

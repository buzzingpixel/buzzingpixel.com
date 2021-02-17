<?php

declare(strict_types=1);

namespace App\Factories;

use Psr\Http\Message\StreamInterface;
use Safe\Exceptions\FilesystemException;
use Slim\Psr7\Stream;

use function Safe\fopen;

class StreamFactory
{
    /**
     * @throws FilesystemException
     */
    public function make(): StreamInterface
    {
        return new Stream(fopen('php://temp', 'r+'));
    }
}

<?php

declare(strict_types=1);

namespace App\Context\DatabaseCache;

use InvalidArgumentException as RootException;
use Psr\Cache\InvalidArgumentException as PsrException;

// phpcs:disable SlevomatCodingStandard.Classes.SuperfluousExceptionNaming.SuperfluousSuffix

class InvalidArgumentException extends RootException implements PsrException
{
}

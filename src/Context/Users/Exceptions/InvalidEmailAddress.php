<?php

declare(strict_types=1);

namespace App\Context\Users\Exceptions;

use Exception;

class InvalidEmailAddress extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Email address must be a valid email address',
        );
    }
}

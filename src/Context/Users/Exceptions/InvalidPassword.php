<?php

declare(strict_types=1);

namespace App\Context\Users\Exceptions;

use Exception;

class InvalidPassword extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Password should be at least 8 characters in length and ' .
                'should include at least one upper case letter, one number, ' .
                'and one special character.',
        );
    }
}

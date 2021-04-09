<?php

declare(strict_types=1);

namespace App\Factories;

use Awurth\SlimValidation\Validator;
use Awurth\SlimValidation\ValidatorInterface;

class ValidationFactory
{
    /**
     * @param string[] $defaultMessages
     */
    public function make(array $defaultMessages = []): ValidatorInterface
    {
        return new Validator(
            false,
            $defaultMessages
        );
    }
}

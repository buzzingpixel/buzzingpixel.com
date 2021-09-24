<?php

declare(strict_types=1);

use App\Cli\Services\CliQuestion;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

return [
    CliQuestion::class => static function (ContainerInterface $di): CliQuestion {
        /** @psalm-suppress MixedArgument */
        return new CliQuestion(
            $di->get(QuestionHelper::class),
            $di->get(ArgvInput::class),
            $di->get(ConsoleOutput::class),
        );
    },
];

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
            questionHelper: $di->get(QuestionHelper::class),
            consoleInput: $di->get(ArgvInput::class),
            consoleOutput: $di->get(ConsoleOutput::class),
        );
    },
];

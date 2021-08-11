<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\VarDumper\Command\Descriptor\HtmlDescriptor;
use Symfony\Component\VarDumper\Command\ServerDumpCommand;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Server\DumpServer;

/**
 * @noinspection PhpUndefinedVariableInspection
 * @psalm-suppress UndefinedGlobalVariable
 * @psalm-suppress RedundantCondition
 * @phpstan-ignore-next-line
 */
assert($app instanceof Application);

if (class_exists(ServerDumpCommand::class)) {
    $input       = new ArgvInput();
    $output      = new ConsoleOutput();
    $defaultHost = '127.0.0.1:9912';

    /** @psalm-suppress MixedAssignment */
    $host = $input->getParameterOption(
        ['--host'],
        $_SERVER['VAR_DUMPER_SERVER'] ?? $defaultHost,
        true
    );

    $logger = interface_exists(LoggerInterface::class) ?
        new ConsoleLogger($output->getErrorOutput()) :
        null;

    $app->getDefinition()->addOption(
        new InputOption(
            '--host',
            null,
            InputOption::VALUE_REQUIRED,
            'The address the server should listen to',
            $defaultHost
        ),
    );

    $htmlDumper = new HtmlDumper();

    $htmlDumper->setTheme('light');

    /** @psalm-suppress MixedArgument */
    $app->add(
        new ServerDumpCommand(
            new DumpServer(
                $host,
                $logger
            ),
            [
                'html' => new HtmlDescriptor($htmlDumper),
            ],
        ),
    );
}

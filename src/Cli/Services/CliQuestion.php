<?php

declare(strict_types=1);

namespace App\Cli\Services;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

use function assert;
use function is_string;

class CliQuestion
{
    private QuestionHelper $questionHelper;
    private InputInterface $consoleInput;
    private OutputInterface $consoleOutput;

    public function __construct(
        QuestionHelper $questionHelper,
        InputInterface $consoleInput,
        OutputInterface $consoleOutput
    ) {
        $this->questionHelper = $questionHelper;
        $this->consoleInput   = $consoleInput;
        $this->consoleOutput  = $consoleOutput;
    }

    public function ask(
        string $question,
        bool $required = true,
        bool $hidden = false
    ): string {
        $questionEntity = new Question($question);

        if ($hidden) {
            $questionEntity->setHidden(true);
        }

        $val = '';

        while ($val === '') {
            /** @psalm-suppress MixedAssignment */
            $val = $this->questionHelper->ask(
                $this->consoleInput,
                $this->consoleOutput,
                $questionEntity
            );
            assert(is_string($val) || $val === null);

            if (! $required) {
                return is_string($val) ? $val : '';
            }

            if ($val !== '' && $val !== null) {
                continue;
            }

            $this->consoleOutput->writeln(
                '<fg=red>You must provide a value</>'
            );
        }

        return is_string($val) ? $val : '';
    }
}

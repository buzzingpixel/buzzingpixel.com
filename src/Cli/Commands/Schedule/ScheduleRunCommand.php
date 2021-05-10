<?php

declare(strict_types=1);

namespace App\Cli\Commands\Schedule;

use App\Context\Schedule\Entities\ScheduleItem;
use App\Context\Schedule\ScheduleApi;
use DateTimeZone;
use Psr\Container\ContainerInterface;
use Safe\DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ScheduleRunCommand extends Command
{
    public function __construct(
        private ScheduleApi $scheduleApi,
        private ContainerInterface $di,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('schedule:run');

        $this->setDescription('Runs scheduled items');
    }

    /** @psalm-suppress PropertyNotSetInConstructor */
    private OutputInterface $output;

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;

        $schedules = $this->scheduleApi->fetchSchedules();

        if ($schedules->isEmpty()) {
            $output->writeln(
                '<fg=yellow>There are no scheduled commands set up</>'
            );

            return 0;
        }

        try {
            $schedules->map(fn (ScheduleItem $i) => $this->processItem(
                $i
            ));
        } catch (Throwable $e) {
            $output->writeln(
                '<fg=red>An unknown error occurred</>'
            );

            return 1;
        }

        return 0;
    }

    protected function processItem(ScheduleItem $scheduleItem): void
    {
        try {
            $this->processItemInner($scheduleItem);
        } catch (Throwable $e) {
            $this->scheduleApi->saveScheduleItem(
                $scheduleItem->withIsRunning(false),
            );

            $this->output->writeln(
                '<fg=red>There was a problem running a scheduled command</>'
            );

            $this->output->writeln(
                '<fg=red>' . $scheduleItem->className() . '</>'
            );

            $this->output->writeln(
                '<fg=red>Message: ' . $e->getMessage() . '</>'
            );
        }
    }

    private function processItemInner(ScheduleItem $scheduleItem): void
    {
        $shouldRun = $this->scheduleApi->checkIfScheduleShouldRun(
            $scheduleItem,
        );

        if ($scheduleItem->isRunning() && ! $shouldRun) {
            $this->output->writeln(
                '<fg=yellow>' .
                    $scheduleItem->className() .
                    ' is currently running</>',
            );

            return;
        }

        if (! $shouldRun) {
            $this->output->writeln(
                '<fg=green>' .
                    $scheduleItem->className() .
                    ' does not need to run at this time</>',
            );

            return;
        }

        $dateTime = new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC')
        );

        $scheduleItem = $scheduleItem
            ->withIsRunning(true)
            ->withLastRunStartAt($dateTime);

        $this->scheduleApi->saveScheduleItem($scheduleItem);

        /** @psalm-suppress MixedAssignment */
        $class = $this->di->get($scheduleItem->className());

        /** @psalm-suppress MixedFunctionCall */
        $class();

        $dateTime = new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC')
        );

        $scheduleItem = $scheduleItem
            ->withIsRunning(false)
            ->withLastRunEndAt($dateTime);

        $this->scheduleApi->saveScheduleItem($scheduleItem);

        $this->output->writeln(
            '<fg=green>' .
                $scheduleItem->className() .
                ' ran successfully</>',
        );
    }
}

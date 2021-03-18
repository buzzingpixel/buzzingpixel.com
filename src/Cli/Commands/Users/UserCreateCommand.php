<?php

declare(strict_types=1);

namespace App\Cli\Commands\Users;

use App\Cli\Services\CliQuestion;
use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Payload\Payload;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function array_walk;

class UserCreateCommand extends Command
{
    public function __construct(
        private CliQuestion $question,
        private UserApi $userApi,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('user:create');

        $this->setDescription('Creates a user');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $emailAddress = $this->question->ask(
            '<fg=cyan>Email address: </>'
        );

        $password = $this->question->ask(
            '<fg=cyan>Password: </>',
            true,
            true
        );

        $payload = $this->userApi->saveUser(
            (new User($emailAddress))
                ->withPassword($password),
        );

        if ($payload->getStatus() !== Payload::STATUS_CREATED) {
            return $this->error($output, $payload);
        }

        return $this->success($output);
    }

    protected function success(OutputInterface $output): int
    {
        $output->writeln(
            '<fg=green>User created.</>'
        );

        return 0;
    }

    protected function error(OutputInterface $output, Payload $payload): int
    {
        $output->writeln(
            '<fg=red>An error occurred</>'
        );

        $result = $payload->getResult();

        array_walk(
            $result,
            static function (
                string $message,
                string $key
            ) use (
                $output
            ): void {
                $output->writeln(
                    '<fg=red>' . $key . ': ' . $message . '</>'
                );
            }
        );

        return 1;
    }
}

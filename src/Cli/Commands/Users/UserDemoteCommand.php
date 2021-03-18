<?php

declare(strict_types=1);

namespace App\Cli\Commands\Users;

use App\Cli\Services\CliQuestion;
use App\Context\Users\UserApi;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function array_walk;

class UserDemoteCommand extends Command
{
    public function __construct(
        private CliQuestion $question,
        private UserApi $userApi,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('user:demote');

        $this->setDescription('Demotes a user from being admin');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = $this->userApi->fetchOneUser(
            (new UserQueryBuilder())
                ->withEmailAddress(
                    $this->question->ask(
                        '<fg=cyan>User\'s Email address: </>'
                    ),
                ),
        );

        if ($user === null) {
            $output->writeln(
                '<fg=red>The user does not exist</>'
            );

            return 1;
        }

        if (! $user->isAdmin()) {
            return $this->error(
                $output,
                new Payload(
                    Payload::STATUS_ERROR,
                    ['message' => 'User is already not an admin'],
                ),
            );
        }

        $user = $user->withIsAdmin(false);

        $payload = $this->userApi->saveUser($user);

        if ($payload->getStatus() !== Payload::STATUS_UPDATED) {
            return $this->error($output, $payload);
        }

        return $this->success($output);
    }

    protected function success(OutputInterface $output): int
    {
        $output->writeln(
            '<fg=green>User was demoted from admin</>'
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

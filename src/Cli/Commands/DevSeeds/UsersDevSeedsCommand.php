<?php

declare(strict_types=1);

namespace App\Cli\Commands\DevSeeds;

use App\Context\Users\Entities\User;
use App\Context\Users\Entities\UserCollection;
use App\Context\Users\UserApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UsersDevSeedsCommand extends Command
{
    public function __construct(
        private UserApi $userApi,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'dev-seeds:users');

        $this->setDescription(description: 'Adds test users');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            messages: '<fg=yellow>Seeding database with test users...</>'
        );

        $users = new UserCollection([
            new User(
                emailAddress: 'testuser1@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser2@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser3@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser4@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser5@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser6@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser7@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser8@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser9@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser10@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser11@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser12@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser13@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser14@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser15@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser16@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser17@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser18@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser19@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser20@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser21@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser22@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser23@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser24@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser25@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser26@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser27@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser28@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser29@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser30@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser31@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser32@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser33@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser34@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser35@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser36@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser37@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser38@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser39@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser40@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser41@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser42@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser43@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser44@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser45@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser46@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser47@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser48@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser49@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser50@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser51@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser52@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser53@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser54@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser55@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser56@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser57@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser58@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser59@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser60@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser61@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser62@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser63@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser64@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser65@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser66@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser67@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser68@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser69@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser70@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser71@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser72@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser73@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser74@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser75@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser76@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser77@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser78@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser79@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser80@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser81@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser82@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser83@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser84@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser85@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser86@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser87@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser88@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser89@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser90@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser91@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser92@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser93@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser94@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser95@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser96@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser97@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser98@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser99@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
            new User(
                emailAddress: 'testuser100@test.com',
                plainTextPassword: 'qwertY1234567*',
            ),
        ]);

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $users->map(function (User $user): void {
            $this->userApi->saveUser($user);
        });

        $output->writeln(messages: '<fg=green>Finished seeding users.</>');

        return 0;
    }
}

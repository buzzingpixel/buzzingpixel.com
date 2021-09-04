<?php

declare(strict_types=1);

namespace App\Cli\Commands\DevSeeds;

use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UsersDeleteDevSeedsCommand extends Command
{
    public function __construct(private UserApi $userApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'dev-seeds:users-delete');

        $this->setDescription(description: 'Removes test users');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            messages: '<fg=yellow>Removing test users from database...</>'
        );

        $users = $this->userApi->fetchUsers(
            queryBuilder: (new UserQueryBuilder())
                ->withEmailAddressIn([
                    'testuser1@test.com',
                    'testuser2@test.com',
                    'testuser3@test.com',
                    'testuser4@test.com',
                    'testuser5@test.com',
                    'testuser6@test.com',
                    'testuser7@test.com',
                    'testuser8@test.com',
                    'testuser9@test.com',
                    'testuser10@test.com',
                    'testuser11@test.com',
                    'testuser12@test.com',
                    'testuser13@test.com',
                    'testuser14@test.com',
                    'testuser15@test.com',
                    'testuser16@test.com',
                    'testuser17@test.com',
                    'testuser18@test.com',
                    'testuser19@test.com',
                    'testuser20@test.com',
                    'testuser21@test.com',
                    'testuser22@test.com',
                    'testuser23@test.com',
                    'testuser24@test.com',
                    'testuser25@test.com',
                    'testuser26@test.com',
                    'testuser27@test.com',
                    'testuser28@test.com',
                    'testuser29@test.com',
                    'testuser30@test.com',
                    'testuser31@test.com',
                    'testuser32@test.com',
                    'testuser33@test.com',
                    'testuser34@test.com',
                    'testuser35@test.com',
                    'testuser36@test.com',
                    'testuser37@test.com',
                    'testuser38@test.com',
                    'testuser39@test.com',
                    'testuser40@test.com',
                    'testuser41@test.com',
                    'testuser42@test.com',
                    'testuser43@test.com',
                    'testuser44@test.com',
                    'testuser45@test.com',
                    'testuser46@test.com',
                    'testuser47@test.com',
                    'testuser48@test.com',
                    'testuser49@test.com',
                    'testuser50@test.com',
                    'testuser51@test.com',
                    'testuser52@test.com',
                    'testuser53@test.com',
                    'testuser54@test.com',
                    'testuser55@test.com',
                    'testuser56@test.com',
                    'testuser57@test.com',
                    'testuser58@test.com',
                    'testuser59@test.com',
                    'testuser60@test.com',
                    'testuser61@test.com',
                    'testuser62@test.com',
                    'testuser63@test.com',
                    'testuser64@test.com',
                    'testuser65@test.com',
                    'testuser66@test.com',
                    'testuser67@test.com',
                    'testuser68@test.com',
                    'testuser69@test.com',
                    'testuser70@test.com',
                    'testuser71@test.com',
                    'testuser72@test.com',
                    'testuser73@test.com',
                    'testuser74@test.com',
                    'testuser75@test.com',
                    'testuser76@test.com',
                    'testuser77@test.com',
                    'testuser78@test.com',
                    'testuser79@test.com',
                    'testuser80@test.com',
                    'testuser81@test.com',
                    'testuser82@test.com',
                    'testuser83@test.com',
                    'testuser84@test.com',
                    'testuser85@test.com',
                    'testuser86@test.com',
                    'testuser87@test.com',
                    'testuser88@test.com',
                    'testuser89@test.com',
                    'testuser90@test.com',
                    'testuser91@test.com',
                    'testuser92@test.com',
                    'testuser93@test.com',
                    'testuser94@test.com',
                    'testuser95@test.com',
                    'testuser96@test.com',
                    'testuser97@test.com',
                    'testuser98@test.com',
                    'testuser99@test.com',
                    'testuser100@test.com',
                ]),
        );

        $users->map(function (User $user): void {
            $this->userApi->deleteUser($user);
        });

        $output->writeln(messages: '<fg=green>Finished removing test users.</>');

        return 0;
    }
}

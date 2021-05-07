<?php

declare(strict_types=1);

namespace App\Cli\Commands\DevSeeds;

use App\Context\Software\Entities\Software;
use App\Context\Software\Entities\SoftwareCollection;
use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SoftwareDevSeedsCommand extends Command
{
    public function __construct(private SoftwareApi $softwareApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(name: 'dev-seeds:software');

        $this->setDescription(description: 'Adds test software');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            messages: '<fg=yellow>Seeding database with test software...</>'
        );

        $software = new SoftwareCollection([
            new Software(
                slug: 'test-software-1',
                name: 'Test Software 1',
                isForSale: true,
                price: 4365,
                renewalPrice: 2365,
                isSubscription: true,
                versions: [
                    new SoftwareVersion(
                        majorVersion: '2',
                        version: '2.0.0',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1399429387
                        ),
                    ),
                    new SoftwareVersion(
                        majorVersion: '1',
                        version: '1.1.0',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1367893387
                        ),
                    ),
                    new SoftwareVersion(
                        majorVersion: '1',
                        version: '1.0.1',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1336357387
                        ),
                    ),
                    new SoftwareVersion(
                        majorVersion: '1',
                        version: '1.0.0',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1273198987
                        ),
                    ),
                ],
            ),
            new Software(
                slug: 'test-software-2',
                name: 'Test Software 2',
                isForSale: true,
                price: 9000,
                versions: [
                    new SoftwareVersion(
                        majorVersion: '1',
                        version: '1.0.0',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1273198987
                        ),
                    ),
                ],
            ),
            new Software(
                slug: 'test-software-3',
                name: 'Test Software 3',
                isForSale: true,
                price: 7099,
                versions: [
                    new SoftwareVersion(
                        majorVersion: '2',
                        version: '2.0.0',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1399429387
                        ),
                    ),
                    new SoftwareVersion(
                        majorVersion: '1',
                        version: '1.1.0',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1367893387
                        ),
                    ),
                    new SoftwareVersion(
                        majorVersion: '1',
                        version: '1.0.1',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1336357387
                        ),
                    ),
                    new SoftwareVersion(
                        majorVersion: '1',
                        version: '1.0.0',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1273198987
                        ),
                    ),
                ],
            ),
            new Software(
                slug: 'test-software-4',
                name: 'Test Software 4',
                versions: [
                    new SoftwareVersion(
                        majorVersion: '2',
                        version: '2.0.0',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1399429387
                        ),
                    ),
                    new SoftwareVersion(
                        majorVersion: '1',
                        version: '1.1.0',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1367893387
                        ),
                    ),
                    new SoftwareVersion(
                        majorVersion: '1',
                        version: '1.0.1',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1336357387
                        ),
                    ),
                    new SoftwareVersion(
                        majorVersion: '1',
                        version: '1.0.0',
                        releasedOn: (new DateTimeImmutable())->setTimestamp(
                            1273198987
                        ),
                    ),
                ],
            ),
        ]);

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $software->map(function (Software $software): void {
            $this->softwareApi->saveSoftware($software);
        });

        $output->writeln(messages: '<fg=green>Finished seeding users.</>');

        return 0;
    }
}

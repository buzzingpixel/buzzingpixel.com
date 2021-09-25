<?php

declare(strict_types=1);

namespace App\Http\Response\Software\ChangelogFeed;

use App\Content\Changelog\ParseChangelogFromMarkdownFile;
use App\Context\Software\Entities\Software;
use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Config\General;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_string;
use function json_encode;
use function mb_stripos;
use function ucfirst;

use const JSON_PRETTY_PRINT;

class ChangelogFeedAction
{
    public const MAP = [
        'ansel-craft' => 'https://raw.githubusercontent.com/buzzingpixel/ansel-craft/master/changelog.md',
        'ansel-ee' => '/src/Http/Response/Software/AnselEE/AnselEEChangelog.md',
        'treasury' => '/src/Http/Response/Software/Treasury/TreasuryChangelog.md',
        'construct' => '/src/Http/Response/Software/Construct/ConstructChangelog.md',
        'category-construct' => '/src/Http/Response/Software/CategoryConstruct/CategoryConstructChangelog.md',
    ];

    public const DOWNLOAD_MAP = [
        'ansel-craft' => 'https://github.com/buzzingpixel/ansel-craft',
        'ansel-ee' => 'https://buzzingpixel.com/software/ansel-ee/download',
        'treasury' => 'https://www.buzzingpixel.com/software/treasury',
        'construct' => 'https://www.buzzingpixel.com/software/construct',
        'category-construct' => 'https://buzzingpixel.com/software/category-construct',
    ];

    public function __construct(
        private General $generalConfig,
        private SoftwareApi $softwareApi,
        private ParseChangelogFromMarkdownFile $parseChangelogFromMarkdownFile,
    ) {
    }

    /**
     * @param mixed[] $arguments
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $arguments
    ): ResponseInterface {
        $for = (string) $arguments['for'];

        $path = self::MAP[(string) $arguments['for']];

        $path = mb_stripos($path, 'http') === 0 ?
            $path :
            $this->generalConfig->rootPath() . $path;

        $changelog = $this->parseChangelogFromMarkdownFile->parse(
            location: $path
        );

        $software = $this->softwareApi->fetchOneSoftware(
            queryBuilder: (new SoftwareQueryBuilder())->withSlug(value: $for),
        );

        assert($software instanceof Software);

        $output = [];

        foreach ($changelog->getReleases() as $release) {
            $softwareVersion = $software->versions()->filter(
                static fn (
                    SoftwareVersion $v
                ) => $v->version() === $release->getVersion(),
            )->firstOrNull();

            $notes = [];

            foreach ($release->getMessageTypesContent() as $type => $item) {
                assert(is_string($type));

                $typeUcFirst = ucfirst($type);
                $notes[]     = '# ' . $typeUcFirst;

                foreach ($item as $itemStr) {
                    $notes[] = '[' . $typeUcFirst . '] ' . $itemStr;
                }
            }

            $date = '';

            if ($softwareVersion !== null) {
                $date = $softwareVersion->releasedOn()->format(
                    DateTimeInterface::ISO8601
                );
            } else {
                $backupDate = DateTimeImmutable::createFromFormat(
                    'Y-m-d h:i:s',
                    $release->getDate() . ' 00:00:00',
                    new DateTimeZone('US/Central'),
                );

                if ($backupDate !== false) {
                    $date = $backupDate->format(
                        DateTimeInterface::ISO8601
                    );
                }
            }

            $output[] = [
                'version' => $release->getVersion(),
                'downloadUrl' => self::DOWNLOAD_MAP[$for],
                'date' => $date,
                'notes' => $notes,
            ];
        }

        $response = $response->withHeader(
            'Content-type',
            'application/json'
        );

        $response->getBody()->write(
            (string) json_encode(
                $output,
                JSON_PRETTY_PRINT,
            ),
        );

        return $response;
    }
}

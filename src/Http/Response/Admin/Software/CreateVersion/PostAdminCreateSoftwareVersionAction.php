<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\CreateVersion;

use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use App\Utilities\DateTimeUtility;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

use function assert;
use function is_array;

class PostAdminCreateSoftwareVersionAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private PostAdminCreateSoftwareVersionResponder $responder,
    ) {
    }

    /**
     * @throws HttpNotFoundException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $softwareSlug = (string) $request->getAttribute('softwareSlug');

        $software = $this->softwareApi->fetchOneSoftware(
            (new SoftwareQueryBuilder())
                ->withSlug($softwareSlug),
        );

        if ($software === null) {
            throw new HttpNotFoundException($request);
        }

        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $redirect = '/admin/software/' . $softwareSlug . '/add-version';

        $releasedOn = DateTimeImmutable::createFromFormat(
            DateTimeUtility::FLATPICKR_DATETIME_LOCAL_FORMAT,
            (string) ($postData['released_on'] ?? ''),
        );

        if ($releasedOn === false) {
            return $this->responder->respond(
                new Payload(
                    Payload::STATUS_NOT_VALID,
                    ['message' => 'Released on was not formatted correctly'],
                ),
                $redirect,
                $postData,
            );
        }

        /**
         * @psalm-suppress RedundantCondition
         * @phpstan-ignore-next-line
         */
        assert($releasedOn instanceof DateTimeImmutable);

        $payload = $this->softwareApi->saveSoftware(
            $software->withAddedVersion(new SoftwareVersion(
                majorVersion: (string) ($postData['major_version'] ?? ''),
                version: (string) ($postData['version'] ?? ''),
                newFileLocation: (string) ($postData['download_file']['file_path'] ?? ''),
                upgradePrice: (int) ((float) ($postData['upgrade_price'] ?? '0') * 100),
                releasedOn: $releasedOn,
            ))
        );

        if ($payload->getStatus() === Payload::STATUS_UPDATED) {
            $redirect = '/admin/software/' . $softwareSlug;
        }

        return $this->responder->respond(
            $payload,
            $redirect,
            $postData,
        );
    }
}

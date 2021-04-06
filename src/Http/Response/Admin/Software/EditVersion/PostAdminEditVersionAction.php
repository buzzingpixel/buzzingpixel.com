<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\EditVersion;

use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use App\Utilities\DateTimeUtility;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

use function assert;
use function is_array;

class PostAdminEditVersionAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private LoggedInUser $loggedInUser,
        private PostAdminEditVersionResponder $responder,
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

        $versionSlug = (string) $request->getAttribute('versionSlug');

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $version = $software->versions()->filter(
            static fn (SoftwareVersion $v) => $v->version() === $versionSlug
        )->firstOrNull();

        if ($version === null) {
            throw new HttpNotFoundException($request);
        }

        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $redirect = $version->adminEditLink();

        $releasedOn = DateTimeImmutable::createFromFormat(
            DateTimeUtility::FLATPICKR_DATETIME_LOCAL_FORMAT,
            (string) ($postData['released_on'] ?? ''),
            $this->loggedInUser->user()->timezone(),
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

        $software->versions()->replaceWhereMatch(
            'id',
            $version
                ->withMajorVersion(
                    (string) ($postData['major_version'] ?? ''),
                )
                ->withVersion((string) ($postData['version'] ?? ''))
                ->withNewFileLocation(
                    (string) ($postData['download_file']['file_path'] ?? ''),
                )
                ->withUpgradePrice(
                    (int) ((float) ($postData['upgrade_price'] ?? '0') * 100),
                )
                ->withReleasedOn($releasedOn),
        );

        $payload = $this->softwareApi->saveSoftware($software);

        if ($payload->getStatus() === Payload::STATUS_UPDATED) {
            $redirect = $version->adminBaseLink();
        }

        return $this->responder->respond(
            $payload,
            $redirect,
            $postData,
        );
    }
}

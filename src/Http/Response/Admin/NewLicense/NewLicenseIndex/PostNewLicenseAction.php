<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex;

use App\Context\Software\SoftwareApi;
use App\Http\Response\Admin\NewLicense\NewLicenseIndex\CreateNewLicense\CreateNewLicenseFactory;
use App\Http\Response\Admin\NewLicense\NewLicenseIndex\GetOrCreateUser\GetOrCreateUserFactory;
use App\Http\Response\Admin\NewLicense\NewLicenseIndex\Responder\PostNewLicenseResponderFactory;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostNewLicenseAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private EntityManager $entityManager,
        private GetOrCreateUserFactory $getOrCreateUserFactory,
        private CreateNewLicenseFactory $createNewLicenseFactory,
        private PostNewLicenseResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->entityManager->beginTransaction();

        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $userEmailAddress = (string) ($postData['user_email_address'] ?? '');

        $user = $this->getOrCreateUserFactory->create(
            userEmailAddress: $userEmailAddress
        )->getOrCreate(userEmailAddress: $userEmailAddress);

        $softwareSlug = (string) ($postData['software_slug'] ?? '');

        $software = $this->softwareApi->fetchOneSoftware(
            queryBuilder: (new SoftwareQueryBuilder())
                ->withSlug($softwareSlug),
        );

        $payload = $this->createNewLicenseFactory->create(
            user: $user,
            software: $software,
        )->create(user: $user, software: $software);

        return $this->responderFactory->create(
            payload: $payload
        )->respond(payload: $payload);
    }
}

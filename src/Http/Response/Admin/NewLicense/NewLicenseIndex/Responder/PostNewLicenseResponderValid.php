<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\Responder;

use App\Context\Licenses\Entities\License;
use App\Payload\Payload;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages as FlashMessages;

use function assert;

class PostNewLicenseResponderValid implements PostNewLicenseResponderContract
{
    public function __construct(
        private EntityManager $entityManager,
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(Payload $payload): ResponseInterface
    {
        $this->entityManager->commit();

        $this->flashMessages->addMessage(
            'FormMessage',
            [
                'status' => Payload::STATUS_SUCCESSFUL,
                'result' => ['message' => 'License created successfully'],
            ]
        );

        $license = $payload->getResult()['licenseEntity'];

        assert($license instanceof License);

        return $this->responseFactory->createResponse(303)->withHeader(
            'Location',
            $license->adminLink(),
        );
    }
}

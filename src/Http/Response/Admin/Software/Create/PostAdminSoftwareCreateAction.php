<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\Create;

use App\Context\Software\Entities\Software;
use App\Context\Software\SoftwareApi;
use App\Payload\Payload;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostAdminSoftwareCreateAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private PostAdminSoftwareCreateResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $payload = $this->softwareApi->saveSoftware(new Software(
            slug: (string) ($postData['slug'] ?? ''),
            name: (string) ($postData['name'] ?? ''),
            isForSale: (bool) ($postData['is_for_sale'] ?? '0'),
            price: (int) ((float) ($postData['price'] ?? '0') * 100),
            renewalPrice: (int) ((float) ($postData['renewal_price'] ?? '0') * 100),
            isSubscription: (bool) ($postData['is_subscription'] ?? '0'),
        ));

        $redirect = '/admin/software/create';

        if ($payload->getStatus() === Payload::STATUS_CREATED) {
            $redirect = '/admin/software';
        }

        return $this->responder->respond(
            $payload,
            $redirect,
            $postData,
        );
    }
}

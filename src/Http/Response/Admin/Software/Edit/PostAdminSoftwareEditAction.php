<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\Edit;

use App\Context\Software\SoftwareApi;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

use function assert;
use function is_array;

class PostAdminSoftwareEditAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private PostAdminSoftwareEditResponder $responder,
    ) {
    }

    /**
     * @throws HttpNotFoundException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $slug = (string) $request->getAttribute('slug');

        $software = $this->softwareApi->fetchOneSoftware(
            (new SoftwareQueryBuilder())
                ->withSlug($slug),
        );

        if ($software === null) {
            throw new HttpNotFoundException($request);
        }

        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $software = $software
            ->withSlug((string) ($postData['slug'] ?? ''))
            ->withName((string) ($postData['name'] ?? ''))
            ->withIsForSale((bool) ($postData['is_for_sale'] ?? '0'))
            ->withPrice((int) ((float) ($postData['price'] ?? '0') * 100))
            ->withRenewalPrice((int) ((float) ($postData['renewal_price'] ?? '0') * 100))
            ->withIsSubscription((bool) ($postData['is_subscription'] ?? '0'));

        $payload = $this->softwareApi->saveSoftware($software);

        $redirect = '/admin/software/' . $slug . '/edit';

        if ($payload->getStatus() === Payload::STATUS_UPDATED) {
            $redirect = '/admin/software/' . $slug;
        }

        return $this->responder->respond(
            $payload,
            $redirect,
            $postData,
        );
    }
}

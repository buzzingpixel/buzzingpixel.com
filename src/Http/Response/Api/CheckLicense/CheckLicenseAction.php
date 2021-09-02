<?php

declare(strict_types=1);

namespace App\Http\Response\Api\CheckLicense;

use App\Http\Response\Api\CheckLicense\Entities\PostValues;
use App\Http\Response\Api\CheckLicense\Factories\CheckLicenseFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class CheckLicenseAction
{
    public function __construct(
        private CheckLicenseFactory $checkLicenseFactory,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $post = $request->getParsedBody();

        assert(is_array($post));

        $postValues = PostValues::fromPostArray(post: $post);

        $responseObject = $this->checkLicenseFactory->getCheckLicense(
            values: $postValues,
        )->check(values: $postValues);

        $response = $this->responseFactory->createResponse()
            ->withHeader(
                'Content-type',
                'application/json'
            );

        $response->getBody()->write($responseObject->toJson());

        return $response;
    }
}

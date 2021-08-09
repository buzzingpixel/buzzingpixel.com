<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

use App\Context\Licenses\LicenseApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Flash\Messages as FlashMessages;

class PostAccountLicenseCancelSubscriptionAction
{
    public function __construct(
        private LicenseApi $licenseApi,
        private LoggedInUser $loggedInUser,
        private FlashMessages $flashMessages,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $licenseKey = (string) $request->getAttribute('licenseKey');

        $license = $this->licenseApi->fetchOneLicense(
            (new LicenseQueryBuilder())
                ->withUserId($this->loggedInUser->user()->id())
                ->withLicenseKey($licenseKey),
        );

        if (
            $license === null ||
            $license->isNotSubscription() ||
            $license->isNotActive()
        ) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $this->licenseApi->cancelSubscription($license);

        $this->flashMessages->addMessage(
            'FormMessage',
            [
                'status' => Payload::STATUS_SUCCESSFUL,
                'result' => ['message' => 'Subscription canceled'],
            ]
        );

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', $license->accountLink());
    }
}

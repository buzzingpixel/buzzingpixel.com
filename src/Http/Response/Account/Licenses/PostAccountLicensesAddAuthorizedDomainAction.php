<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

use App\Context\Licenses\LicenseApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

use function assert;
use function is_array;

class PostAccountLicensesAddAuthorizedDomainAction
{
    public function __construct(
        private LicenseApi $licenseApi,
        private LoggedInUser $loggedInUser,
        private PostAccountLicensesAddAuthorizedDomainResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $licenseKey = (string) $request->getAttribute('licenseKey');

        $license = $this->licenseApi->fetchOneLicense(
            (new LicenseQueryBuilder())
                ->withUserId($this->loggedInUser->user()->id())
                ->withHasBeenUpgraded(false)
                ->withLicenseKey($licenseKey),
        );

        if ($license === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        if ($license->hasReachedMaxDomains()) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $postData = $request->getParsedBody();

        assert(is_array($postData));

        $postedDomainName = (string) ($postData['domain_name'] ?? '');

        if ($postedDomainName === '') {
            return $this->responder->respond(
                payload: new Payload(
                    status: Payload::STATUS_NOT_VALID,
                    result: ['message' => 'Domain Name must not be empty']
                ),
                redirectTo: $license->accountAddAuthorizedDomainLink(),
            );
        }

        $license = $license->withAuthorizedDomain(
            authorizedDomain: $postedDomainName,
        );

        $payload = $this->licenseApi->saveLicense($license);

        $redirectTo = $payload->getStatus() === Payload::STATUS_UPDATED ?
            $license->accountLink() :
            $license->accountAddAuthorizedDomainLink();

        return $this->responder->respond(
            payload: $payload,
            redirectTo: $redirectTo,
        );
    }
}

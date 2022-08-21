<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses;

use App\Context\Licenses\LicenseApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

use function base64_decode;

class AccountLicenseDeleteAuthorizedDomainAction
{
    public function __construct(
        private LicenseApi $licenseApi,
        private LoggedInUser $loggedInUser,
        private AccountLicenseDeleteAuthorizedDomainResponder $responder,
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

        /** @phpstan-ignore-next-line */
        $domainName = base64_decode(
            (string) $request->getAttribute('domainName'),
        );

        $license = $license->withRemovedAuthorizedDomain(
            $domainName
        );

        $payload = $this->licenseApi->saveLicense($license);

        return $this->responder->respond(
            payload: $payload,
            redirectTo: $license->accountLink(),
        );
    }
}

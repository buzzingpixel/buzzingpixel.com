<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\View;

use App\Context\Licenses\LicenseApi;
use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class UserLicenseDisableAction
{
    public function __construct(
        private UserApi $userApi,
        private LicenseApi $licenseApi,
        private UserLicenseDisableResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $emailAddress = (string) $request->getAttribute('emailAddress');

        $user = $this->userApi->fetchOneUser(
            queryBuilder: (new UserQueryBuilder())
                ->withEmailAddress($emailAddress),
        );

        if ($user === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $licenseKey = (string) $request->getAttribute('licenseKey');

        $license = $this->licenseApi->fetchOneLicense(
            queryBuilder: (new LicenseQueryBuilder())
                ->withLicenseKey($licenseKey)
                ->withUserId($user->id()),
        );

        if ($license === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $license = $license->withIsDisabled(isDisabled: true);

        $payload = $this->licenseApi->saveLicense($license);

        return $this->responder->respond(
            payload: $payload,
            redirectTo: $license->adminLink(),
        );
    }
}

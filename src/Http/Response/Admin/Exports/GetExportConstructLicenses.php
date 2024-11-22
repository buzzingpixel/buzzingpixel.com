<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Exports;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use League\Csv\Writer as CsvWriter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function implode;
use function in_array;
use function mb_strlen;

class GetExportConstructLicenses
{
    public function __construct(private LicenseApi $licenseApi)
    {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $licenseCollection = $this->licenseApi->fetchLicenses(
            queryBuilder: new LicenseQueryBuilder(),
        );

        $constructLicenses = $licenseCollection->filter(
            static function (License $license): bool {
                return in_array(
                    $license->software()->slug(),
                    [
                        'construct',
                        'category-construct',
                    ]
                );
            }
        );

        $csv = CsvWriter::createFromString();

        $csv->insertOne([
            'ID',
            'isDisabled',
            'majorVersion',
            'maxVersion',
            'licenseKey',
            'userNotes',
            'adminNotes',
            'authorizedDomains',
            'expiresAt',
            'emailAddress',
            'billingName',
            'billingCompany',
            'billingPhone',
            'billingCountryRegion',
            'billingAddress',
            'billingAddressContinued',
            'billingCity',
            'billingStateProvince',
            'billingPostalCode',
            'displayName',
            'software',
            'isUpgrade',
            'hasBeenUpgraded',
        ]);

        $constructLicenses->map(
            static function (License $license) use ($csv): void {
                $expiresAt = $license->expiresAt();

                if ($expiresAt === null) {
                    $expiresAt = '';
                } else {
                    $expiresAt = $expiresAt->getTimestamp();
                }

                $user = $license->user();

                $billingProfile = $user->billingProfile();

                $csv->insertOne([
                    $license->id(),
                    $license->isDisabled() ? 'true' : 'false',
                    $license->majorVersion(),
                    $license->maxVersion(),
                    $license->licenseKey(),
                    $license->userNotes(),
                    $license->adminNotes(),
                    implode(',', $license->authorizedDomains()),
                    $expiresAt,
                    $user->emailAddress(),
                    $billingProfile->billingName(),
                    $billingProfile->billingCompany(),
                    $billingProfile->billingPhone(),
                    $billingProfile->billingCountryRegion(),
                    $billingProfile->billingAddress(),
                    $billingProfile->billingAddressContinued(),
                    $billingProfile->billingCity(),
                    $billingProfile->billingStateProvince(),
                    $billingProfile->billingPostalCode(),
                    $user->supportProfile()->displayName(),
                    $license->software()->slug(),
                    $license->isUpgrade() ? 'true' : 'false',
                    $license->hasBeenUpgraded() ? 'true' : 'false',
                ]);
            }
        );

        $csvString = $csv->toString();

        $response->getBody()->write($csvString);

        return $response->withHeader(
            'Content-Disposition',
            'attachment; filename="construct-licenses.csv"'
        )
            ->withHeader('Content-Type', 'text/plain')
            ->withHeader('Content-Length', mb_strlen($csvString))
            ->withHeader('Connection', 'close');
    }
}

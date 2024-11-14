<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Exports;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderItem;
use App\Context\Orders\OrderApi;
use App\Persistence\QueryBuilders\LicenseQueryBuilder\LicenseQueryBuilder;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use League\Csv\Writer as CsvWriter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function in_array;
use function mb_strlen;

class GetExportLicensesForBrian
{
    public function __construct(
        private OrderApi $orderApi,
        private LicenseApi $licenseApi,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $orderCollection = $this->orderApi->fetchOrders(
            queryBuilder: new OrderQueryBuilder(),
        );

        $licenseCollection = $this->licenseApi->fetchLicenses(
            queryBuilder: new LicenseQueryBuilder(),
        );

        $anselLicenses = $licenseCollection->filter(
            static function (License $license): bool {
                return in_array(
                    $license->software()->slug(),
                    [
                        'ansel-treasury-ee',
                        'ansel-craft',
                        'ansel-ee',
                    ]
                );
            }
        );

        $csv = CsvWriter::createFromString();

        $csv->insertOne([
            'title',
            'url_title',
            'email',
            'license_key',
            'purchase_date',
            'price',
            'notes',
            'expiration_date',
        ]);

        $anselLicenses->map(
            static function (License $license) use (
                $csv,
                $orderCollection,
            ): void {
                $licenseKey = $license->licenseKey();

                $order = $orderCollection->filter(
                    static function (Order $order) use ($licenseKey): bool {
                        $isLicense = false;

                        foreach ($order->orderItems()->toArray() as $item) {
                            if ($item->license()->licenseKey() !== $licenseKey) {
                                continue;
                            }

                            $isLicense = true;

                            break;
                        }

                        return $isLicense;
                    }
                )->firstOrNull();

                $orderItem = $order?->orderItems()->filter(
                    static function (OrderItem $item) use ($licenseKey): bool {
                        return $item->license()->licenseKey() === $licenseKey;
                    }
                )->firstOrNull();

                $expiresAt = $license->expiresAt();

                if ($expiresAt === null) {
                    $expiresAt = '';
                } else {
                    $expiresAt = $expiresAt->format('Y-m-d');
                }

                $user = $license->user();

                $software = $license->software();

                $csv->insertOne([
                    $software->name(),
                    $software->slug(),
                    $user->emailAddress(),
                    $licenseKey,
                    $order?->orderDate()?->format('Y-m-d') ?? null,
                    $orderItem?->priceFormattedNoSymbol(),
                    $license->userNotes(),
                    $expiresAt,
                ]);
            }
        );

        $csvString = $csv->toString();

        $response->getBody()->write($csvString);

        return $response->withHeader(
            'Content-Disposition',
            'attachment; filename="ansel-licenses-for-brian.csv"'
        )
            ->withHeader('Content-Type', 'text/plain')
            ->withHeader('Content-Length', mb_strlen($csvString))
            ->withHeader('Connection', 'close');
    }
}

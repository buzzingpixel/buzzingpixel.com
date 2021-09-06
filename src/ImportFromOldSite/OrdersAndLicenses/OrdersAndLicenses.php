<?php

declare(strict_types=1);

namespace App\ImportFromOldSite\OrdersAndLicenses;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\LicenseApi;
use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderItem;
use App\Context\Orders\Entities\OrderItemCollection;
use App\Context\Orders\OrderApi;
use App\Context\Software\SoftwareApi;
use App\Context\Users\UserApi;
use App\Payload\Payload;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use DateTimeImmutable;
use DateTimeInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

use function array_map;
use function assert;
use function json_decode;
use function var_export;

class OrdersAndLicenses
{
    public function __construct(
        private Client $guzzle,
        private General $config,
        private UserApi $userApi,
        private OrderApi $orderApi,
        private LicenseApi $licenseApi,
        private SoftwareApi $softwareApi,
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function import(): void
    {
        $request = $this->guzzle->get(
            $this->config->oldSiteUrl(
                uri: '/new-site-transfer/orders-and-licenses'
            ),
            [
                'query' => ['key' => $this->config->oldSiteTransferKey()],
            ],
        );

        // dd(count(json_decode((string) $request->getBody(), true)));

        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedArrayAccess
         */
        array_map(
            [$this, 'importItem'],
            json_decode((string) $request->getBody(), true)
        );
    }

    /**
     * @param mixed[] $item
     */
    protected function importItem(array $item): void
    {
        $alreadyImportedOrder = $this->orderApi->fetchOneOrder(
            queryBuilder: (new OrderQueryBuilder())
                ->withWhere(
                    'oldOrderNumber',
                    (string) $item['oldOrderNumber']
                ),
        );

        if ($alreadyImportedOrder !== null) {
            return;
        }

        $user = $this->userApi->fetchOneUser(
            queryBuilder: (new UserQueryBuilder())
                ->withEmailAddress(value: (string) $item['userEmail']),
        );

        $orderDate = DateTimeImmutable::createFromFormat(
            DateTimeInterface::ATOM,
            (string) $item['orderDate'],
        );

        assert($orderDate instanceof DateTimeInterface);

        $order = new Order(
            oldOrderNumber: (string) $item['oldOrderNumber'],
            stripeId: (string) $item['stripeId'],
            stripeAmount: (string) $item['stripeAmount'],
            stripeBalanceTransaction: (string) $item['stripeBalanceTransaction'],
            stripeCaptured: (bool) $item['stripeCaptured'],
            stripeCreated: (string) $item['stripeCreated'],
            stripeCurrency: (string) $item['stripeCurrency'],
            stripePaid: (bool) $item['stripePaid'],
            subTotal: (int) $item['subTotal'],
            tax: (int) $item['tax'],
            total: (int) $item['total'],
            billingName: (string) $item['billingName'],
            billingCompany: (string) $item['billingCompany'],
            billingPhone: (string) $item['billingPhone'],
            billingCountryRegion: (string) $item['billingCountryRegion'],
            billingAddress: (string) $item['billingAddress'],
            billingAddressContinued: (string) $item['billingAddressContinued'],
            billingCity: (string) $item['billingCity'],
            billingStateProvince: (string) $item['billingStateProvince'],
            billingPostalCode: (string) $item['billingPostalCode'],
            orderDate: $orderDate,
            user: $user,
        );

        $order = $order->withOrderItems(new OrderItemCollection(
            array_map(
                function (array $orderItem) use (
                    $order,
                    $user,
                ): OrderItem {
                    $software = $this->softwareApi->fetchOneSoftware(
                        queryBuilder: (new SoftwareQueryBuilder())
                            ->withSlug(
                                value: (string) $orderItem['softwareSlug']
                            ),
                    );

                    /**
                     * @psalm-suppress MixedArgumentTypeCoercion
                     */
                    $license = new License(
                        isDisabled: (bool) $orderItem['isDisabled'],
                        majorVersion: (string) $orderItem['softwareVersion'],
                        licenseKey: (string) $orderItem['licenseKey'],
                        userNotes: (string) $orderItem['userNotes'],
                        authorizedDomains: (array) $orderItem['authorizedDomains'],
                        user: $user,
                        software: $software,
                        isUpgrade: (bool) $orderItem['isUpgrade'],
                        hasBeenUpgraded: (bool) $orderItem['hasBeenUpgraded'],
                    );

                    $this->licenseApi->saveLicense(license: $license);

                    return new OrderItem(
                        price: (int) $orderItem['price'],
                        originalPrice: (int) $orderItem['originalPrice'],
                        order: $order,
                        software: $software,
                        license: $license,
                    );
                },
                (array) $item['orderItems'],
            ),
        ));

        $payload = $this->orderApi->saveOrder(order: $order);

        if ($payload->getStatus() === Payload::STATUS_CREATED) {
            return;
        }

        var_export($payload);
        die;
    }
}

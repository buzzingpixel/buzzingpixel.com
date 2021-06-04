<?php

declare(strict_types=1);

namespace App\Context\Stripe\Services;

use App\Context\Software\Entities\Software;
use App\Context\Software\SoftwareApi;
use App\Context\Stripe\Factories\StripeFactory;
use App\Context\Stripe\Factories\SyncProductFactory;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Stripe\Product;
use Stripe\StripeClient;

use function array_map;
use function in_array;

class SyncProducts
{
    private StripeClient $stripeClient;

    public function __construct(
        StripeFactory $stripeFactory,
        private SoftwareApi $softwareApi,
        private SyncProductFactory $syncProductFactory,
    ) {
        $this->stripeClient = $stripeFactory->createStripeClient();
    }

    public function sync(): void
    {
        // Get all of our software in our local system
        $softwares = $this->softwareApi->fetchSoftware(
            new SoftwareQueryBuilder()
        );

        /** @var Product[] $products */
        $products = $this->stripeClient->products->all()->data;

        // Sync all products that already exist on Stripe, save slugs of the
        // updated items
        $updatedSoftwareSlugs = array_map(
            function (Product $product) use ($softwares): ?string {
                $software = $softwares->where(
                    'slug',
                    /** @phpstan-ignore-next-line  */
                    $product->metadata->slug,
                )->firstOrNull();

                $this->syncProductFactory
                    ->createSyncProduct(
                        product: $product,
                        software: $software,
                    )
                    ->sync();

                if ($software === null) {
                    return null;
                }

                return $software->slug();
            },
            $products,
        );

        // Add new software to stripe
        $softwares->map(function (Software $software) use (
            $updatedSoftwareSlugs
        ): void {
            if (
                in_array(
                    $software->slug(),
                    $updatedSoftwareSlugs,
                    true,
                )
            ) {
                return;
            }

            $this->syncProductFactory->createSyncProduct(
                software: $software,
            )
            ->sync();
        });
    }
}

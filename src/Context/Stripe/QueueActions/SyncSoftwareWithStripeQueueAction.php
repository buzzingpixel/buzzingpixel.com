<?php

declare(strict_types=1);

namespace App\Context\Stripe\QueueActions;

use App\Context\Software\Entities\Software;
use App\Context\Software\SoftwareApi;
use App\Context\Stripe\Factories\SyncProductFactory;
use App\Context\Stripe\Services\StripeFetchProducts;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Stripe\Product;

use function array_key_exists;
use function assert;

class SyncSoftwareWithStripeQueueAction
{
    public function __construct(
        private StripeFetchProducts $stripeFetchProducts,
        private SoftwareApi $softwareApi,
        private SyncProductFactory $syncProductFactory,
    ) {
    }

    /**
     * @param array<string, string> $context
     */
    public function sync(array $context): void
    {
        assert(array_key_exists('softwareId', $context));

        $software = $this->softwareApi->fetchOneSoftware(
            (new SoftwareQueryBuilder())
                ->withId($context['softwareId']),
        );

        assert($software instanceof Software);

        $product = $this->stripeFetchProducts->fetch()
            ->filter(static fn (Product $p) => $p->metadata['slug'] === $software->slug())
            ->firstOrNull();

        $this->syncProductFactory
            ->createSyncProduct(
                product: $product,
                software: $software,
            )
            ->sync();
    }
}

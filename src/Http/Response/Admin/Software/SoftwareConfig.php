<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software;

use App\Context\Software\Entities\Software;

use function count;

class SoftwareConfig
{
    /**
     * @param mixed[] $postData
     *
     * @return mixed[]
     */
    public static function getCreateEditFormConfigInputs(
        array $postData,
        ?Software $software = null,
    ): array {
        $hasPostData = count($postData) > 0;

        if ($software === null) {
            $software = new Software();
        }

        return [
            [
                'label' => 'Slug',
                'name' => 'slug',
                'value' => $hasPostData ?
                    (string) ($postData['slug'] ?? '') :
                    $software->slug(),
            ],
            [
                'label' => 'Name',
                'name' => 'name',
                'value' => $hasPostData ?
                    (string) ($postData['name'] ?? '') :
                    $software->name(),
            ],
            [
                'template' => 'Toggle',
                'label' => 'Is For Sale?',
                'name' => 'is_for_sale',
                'value' => $hasPostData ?
                    (bool) ($postData['is_for_sale'] ?? '0') :
                    $software->isForSale(),
            ],
            [
                'template' => 'Price',
                'label' => 'Price',
                'name' => 'price',
                'value' => $hasPostData ?
                    (string) ($postData['price'] ?? '') :
                    $software->priceFormattedNoSymbol(),
            ],
            [
                'template' => 'Price',
                'label' => 'Renewal Price',
                'name' => 'renewal_price',
                'value' => $hasPostData ?
                    (string) ($postData['renewal_price'] ?? '') :
                    $software->renewalPriceFormattedNoSymbol(),
            ],
            [
                'template' => 'Toggle',
                'label' => 'Is Subscription?',
                'name' => 'is_subscription',
                'value' => $hasPostData ?
                    (bool) ($postData['is_subscription'] ?? '0') :
                    $software->isSubscription(),
            ],
        ];
    }
}

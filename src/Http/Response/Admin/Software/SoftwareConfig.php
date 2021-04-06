<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software;

use App\Context\Software\Entities\Software;
use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Users\Entities\User;
use App\Utilities\DateTimeUtility;
use DateTimeImmutable;

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

    /**
     * @param mixed[] $postData
     *
     * @return mixed[]
     */
    public static function getCreateEditVersionFormConfigInputs(
        User $user,
        array $postData,
        ?SoftwareVersion $version = null,
    ): array {
        $hasPostData = count($postData) > 0;

        if ($version === null) {
            $version = new SoftwareVersion();
        }

        return [
            [
                'label' => 'Major Version',
                'name' => 'major_version',
                'value' => $hasPostData ?
                    (string) ($postData['major_version'] ?? '') :
                    $version->majorVersion(),
            ],
            [
                'label' => 'Version',
                'name' => 'version',
                'value' => $hasPostData ?
                    (string) ($postData['version'] ?? '') :
                    $version->version(),
            ],
            [
                'template' => 'FileUpload',
                'label' => 'Download File',
                'name' => 'download_file',
                'value' => $hasPostData ?
                    $postData['download_file'] ?? '' :
                    $version->downloadFileName(),
            ],
            [
                'template' => 'Price',
                'label' => 'Upgrade Price',
                'name' => 'upgrade_price',
                'value' => $hasPostData ?
                    (string) ($postData['upgrade_price'] ?? '') :
                    $version->upgradePriceFormattedNoSymbol(),
            ],
            [
                'type' => 'datetime-local',
                'label' => 'Released On',
                'name' => 'released_on',
                'value' => $hasPostData ?
                    (string) (
                        $postData['released_on'] ?? (new DateTimeImmutable())
                            ->setTimezone($user->timezone())
                            ->format(DateTimeUtility::FLATPICKR_DATETIME_LOCAL_FORMAT)
                    ) :
                    $version->releasedOn()
                        ->setTimezone($user->timezone())
                        ->format(DateTimeUtility::FLATPICKR_DATETIME_LOCAL_FORMAT),
            ],
        ];
    }
}

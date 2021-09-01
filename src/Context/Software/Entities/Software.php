<?php

declare(strict_types=1);

namespace App\Context\Software\Entities;

use App\EntityPropertyTraits\BundledSoftware;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\IsForSale;
use App\EntityPropertyTraits\IsSubscription;
use App\EntityPropertyTraits\Name;
use App\EntityPropertyTraits\Price;
use App\EntityPropertyTraits\RenewalPrice;
use App\EntityPropertyTraits\Slug;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\Entities\Software\SoftwareVersionRecord;
use LogicException;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

use function array_map;
use function array_merge;
use function implode;
use function is_array;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class Software
{
    use Id;
    use Slug;
    use Name;
    use IsForSale;
    use Price;
    use RenewalPrice;
    use IsSubscription;
    use BundledSoftware;

    /** @phpstan-ignore-next-line */
    private SoftwareVersionCollection $versions;

    public static function fromRecord(SoftwareRecord $record): self
    {
        return (new self(
            id: $record->getId(),
            slug: $record->getSlug(),
            name: $record->getName(),
            isForSale: $record->getIsForSale(),
            price: $record->getPrice(),
            renewalPrice: $record->getRenewalPrice(),
            isSubscription: $record->getIsSubscription(),
            bundledSoftware: $record->getBundledSoftware(),
        ))->withVersionsFromRecord($record);
    }

    /**
     * @param string[] $bundledSoftware
     *
     * @phpstan-ignore-next-line
     */
    public function __construct(
        string $slug = '',
        string $name = '',
        bool $isForSale = false,
        int | Money $price = 0,
        int | Money $renewalPrice = 0,
        bool $isSubscription = false,
        array $bundledSoftware = [],
        null | array | SoftwareVersionCollection $versions = null,
        null | string | UuidInterface $id = null,
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'This object can only be constructed once'
            );
        }

        if ($id === null) {
            $this->id = IdValue::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = IdValue::fromString($id->toString());
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->slug = $slug;

        $this->name = $name;

        $this->isForSale = $isForSale;

        if ($price instanceof Money) {
            $this->price = $price;
        } else {
            $this->price = new Money(
                $price,
                new Currency('USD')
            );
        }

        if ($renewalPrice instanceof Money) {
            $this->renewalPrice = $renewalPrice;
        } else {
            $this->renewalPrice = new Money(
                $renewalPrice,
                new Currency('USD')
            );
        }

        $this->isSubscription = $isSubscription;

        $this->bundledSoftware = $bundledSoftware;

        if ($versions === null) {
            $this->versions = new SoftwareVersionCollection();
        } elseif (is_array($versions)) {
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $this->versions = new SoftwareVersionCollection($versions);
        } else {
            $this->versions = $versions;
        }

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    /** @phpstan-ignore-next-line */
    public function versions(): SoftwareVersionCollection
    {
        return $this->versions;
    }

    /** @phpstan-ignore-next-line */
    public function withVersions(array | SoftwareVersionCollection $versions): self
    {
        $clone = clone $this;

        if (! is_array($versions)) {
            $versions = $versions->toArray();
        }

        $clone->versions = new SoftwareVersionCollection(array_map(
            static fn (SoftwareVersion $v) => $v->withSoftware(
                $clone,
            ),
            array_merge($versions)
        ));

        return $clone;
    }

    public function withAddedVersion(SoftwareVersion $newVersion): self
    {
        $clone = clone $this;

        $clone->versions = new SoftwareVersionCollection(array_map(
            static fn (SoftwareVersion $v) => $v->withSoftware(
                $clone
            ),
            array_merge(
                $this->versions->toArray(),
                [$newVersion],
            ),
        ));

        return $clone;
    }

    public function withVersionsFromRecord(SoftwareRecord $record): self
    {
        $clone = clone $this;

        $clone->versions = new SoftwareVersionCollection(array_map(
            static fn (
                SoftwareVersionRecord $r
            ) => SoftwareVersion::fromRecord(
                $r,
                $clone,
            ),
            $record->getVersions()->toArray(),
        ));

        return $clone;
    }

    public function withVersionFromVersionRecord(
        SoftwareVersionRecord $record,
    ): self {
        $clone = clone $this;

        $versions = new SoftwareVersionCollection(
            $this->versions->toArray(),
        );

        $versions->add(SoftwareVersion::fromRecord(
            $record,
            $clone,
        ));

        $clone->versions = $versions;

        return $clone;
    }

    public function priceLessSubscriptionAsInt(): int
    {
        return $this->priceAsInt() - $this->renewalPriceAsInt();
    }

    public function adminBaseLink(): string
    {
        return '/' . implode(
            '/',
            [
                'admin',
                'software',
                $this->slug(),
            ],
        );
    }

    public function adminDeleteLink(): string
    {
        return implode(
            '/',
            [
                $this->adminBaseLink(),
                'delete',
            ]
        );
    }

    public function adminEditLink(): string
    {
        return implode(
            '/',
            [
                $this->adminBaseLink(),
                'edit',
            ]
        );
    }

    public function adminAddVersionLink(): string
    {
        return implode(
            '/',
            [
                $this->adminBaseLink(),
                'add-version',
            ]
        );
    }

    public function pageLink(): string
    {
        return '/' . implode(
            '/',
            [
                'software',
                $this->slug(),
            ],
        );
    }
}

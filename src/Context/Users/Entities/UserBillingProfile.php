<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\EntityPropertyTraits\BillingAddress;
use App\EntityPropertyTraits\BillingAddressContinued;
use App\EntityPropertyTraits\BillingCity;
use App\EntityPropertyTraits\BillingCompany;
use App\EntityPropertyTraits\BillingCountryRegion;
use App\EntityPropertyTraits\BillingName;
use App\EntityPropertyTraits\BillingPhone;
use App\EntityPropertyTraits\BillingPostalCode;
use App\EntityPropertyTraits\BillingStateProvince;
use App\EntityPropertyTraits\Id;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Users\UserBillingProfileRecord;
use LogicException;
use Ramsey\Uuid\UuidInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class UserBillingProfile
{
    use Id;
    use BillingName;
    use BillingCompany;
    use BillingPhone;
    use BillingCountryRegion;
    use BillingAddress;
    use BillingAddressContinued;
    use BillingCity;
    use BillingStateProvince;
    use BillingPostalCode;

    public static function fromRecord(UserBillingProfileRecord $record): self
    {
        return new self(
            id: $record->getId(),
            billingName: $record->getBillingName(),
            billingCompany: $record->getBillingCompany(),
            billingPhone: $record->getBillingPhone(),
            billingCountryRegion: $record->getBillingCountryRegion(),
            billingAddress: $record->getBillingAddress(),
            billingAddressContinued: $record->getBillingAddressContinued(),
            billingCity: $record->getBillingCity(),
            billingStateProvince: $record->getBillingStateProvince(),
            billingPostalCode: $record->getBillingPostalCode(),
        );
    }

    public function __construct(
        string $billingName = '',
        string $billingCompany = '',
        string $billingPhone = '',
        string $billingCountryRegion = '',
        string $billingAddress = '',
        string $billingAddressContinued = '',
        string $billingCity = '',
        string $billingStateProvince = '',
        string $billingPostalCode = '',
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

        $this->billingName = $billingName;

        $this->billingCompany = $billingCompany;

        $this->billingPhone = $billingPhone;

        $this->billingCountryRegion = $billingCountryRegion;

        $this->billingAddress = $billingAddress;

        $this->billingAddressContinued = $billingAddressContinued;

        $this->billingCity = $billingCity;

        $this->billingStateProvince = $billingStateProvince;

        $this->billingPostalCode = $billingPostalCode;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;
}

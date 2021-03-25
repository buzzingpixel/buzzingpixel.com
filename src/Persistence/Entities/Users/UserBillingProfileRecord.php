<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Users;

use App\Context\Users\Entities\UserBillingProfile;
use App\Persistence\PropertyTraits\BillingAddress;
use App\Persistence\PropertyTraits\BillingAddressContinued;
use App\Persistence\PropertyTraits\BillingCity;
use App\Persistence\PropertyTraits\BillingCompany;
use App\Persistence\PropertyTraits\BillingCountryRegion;
use App\Persistence\PropertyTraits\BillingName;
use App\Persistence\PropertyTraits\BillingPhone;
use App\Persistence\PropertyTraits\BillingPostalCode;
use App\Persistence\PropertyTraits\BillingStateProvince;
use App\Persistence\PropertyTraits\Id;
use Doctrine\ORM\Mapping;
use Ramsey\Uuid\Uuid;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="user_billing_profiles")
 */
class UserBillingProfileRecord
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

    public function hydrateFromEntity(UserBillingProfile $billingProfile): void
    {
        $this->setId(Uuid::fromString($billingProfile->id()));
        $this->setBillingName($billingProfile->billingName());
        $this->setBillingCompany(
            $billingProfile->billingCompany(),
        );
        $this->setBillingPhone($billingProfile->billingPhone());
        $this->setBillingCountryRegion(
            $billingProfile->billingCountryRegion(),
        );
        $this->setBillingAddress(
            $billingProfile->billingAddress(),
        );
        $this->setBillingAddressContinued(
            $billingProfile->billingAddressContinued(),
        );
        $this->setBillingCity($billingProfile->billingCity());
        $this->setBillingStateProvince(
            $billingProfile->billingStateProvince(),
        );
        $this->setBillingPostalCode(
            $billingProfile->billingPostalCode(),
        );
    }
}

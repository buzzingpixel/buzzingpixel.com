<?php

declare(strict_types=1);

namespace App\Context\Licenses\Entities;

use App\Context\Software\Entities\Software as SoftwareEntity;
use App\Context\Users\Entities\User as UserEntity;
use App\EntityPropertyTraits\AdminNotes;
use App\EntityPropertyTraits\AuthorizedDomains;
use App\EntityPropertyTraits\ExpiresAt;
use App\EntityPropertyTraits\HasBeenUpgraded;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\IsDisabled;
use App\EntityPropertyTraits\IsUpgrade;
use App\EntityPropertyTraits\LicenseKey;
use App\EntityPropertyTraits\MajorVersion;
use App\EntityPropertyTraits\Software;
use App\EntityPropertyTraits\User;
use App\EntityPropertyTraits\UserNotes;
use App\EntityValueObjects\Id as IdValue;
use App\Utilities\DateTimeUtility;
use DateTimeInterface;
use LogicException;
use Ramsey\Uuid\UuidInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class License
{
    use Id;
    use IsDisabled;
    use MajorVersion;
    use IsUpgrade;
    use HasBeenUpgraded;
    use LicenseKey;
    use UserNotes;
    use AdminNotes;
    use AuthorizedDomains;
    use ExpiresAt;
    use User;
    use Software;

    /**
     * @param string[] $authorizedDomains
     */
    public function __construct(
        bool $isDisabled = false,
        string $majorVersion = '',
        bool $isUpgrade = false,
        bool $hasBeenUpgraded = false,
        string $licenseKey = '',
        string $userNotes = '',
        string $adminNotes = '',
        array $authorizedDomains = [],
        null | string | DateTimeInterface $expiresAt = null,
        ?UserEntity $user = null,
        ?SoftwareEntity $software = null,
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

        $this->isDisabled = $isDisabled;

        $this->majorVersion = $majorVersion;

        $this->isUpgrade = $isUpgrade;

        $this->hasBeenUpgraded = $hasBeenUpgraded;

        $this->licenseKey = $licenseKey;

        $this->userNotes = $userNotes;

        $this->adminNotes = $adminNotes;

        $this->authorizedDomains = $authorizedDomains;

        $this->expiresAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $expiresAt,
        );

        $this->user = $user;

        $this->software = $software;
    }

    private bool $isInitialized = false;
}

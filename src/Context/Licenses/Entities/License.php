<?php

declare(strict_types=1);

namespace App\Context\Licenses\Entities;

use App\Context\Software\Entities\Software as SoftwareEntity;
use App\Context\Users\Entities\User as UserEntity;
use App\EntityPropertyTraits\AdminNotes;
use App\EntityPropertyTraits\AuthorizedDomains;
use App\EntityPropertyTraits\ExpiresAt;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\IsDisabled;
use App\EntityPropertyTraits\LicenseKey;
use App\EntityPropertyTraits\MajorVersion;
use App\EntityPropertyTraits\Software;
use App\EntityPropertyTraits\StripeCanceledAt;
use App\EntityPropertyTraits\StripeStatus;
use App\EntityPropertyTraits\StripeSubscriptionAmount;
use App\EntityPropertyTraits\StripeSubscriptionId;
use App\EntityPropertyTraits\StripeSubscriptionItemId;
use App\EntityPropertyTraits\User;
use App\EntityPropertyTraits\UserNotes;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Licenses\LicenseRecord;
use App\Utilities\DateTimeUtility;
use DateTimeImmutable;
use DateTimeInterface;
use LogicException;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

use function implode;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class License
{
    public const STRIPE_STATUS_ACTIVE             = 'active';
    public const STRIPE_STATUS_PAST_DUE           = 'past_due';
    public const STRIPE_STATUS_UNPAID             = 'unpaid';
    public const STRIPE_STATUS_CANCELED           = 'canceled';
    public const STRIPE_STATUS_INCOMPLETE         = 'incomplete';
    public const STRIPE_STATUS_INCOMPLETE_EXPIRED = 'incomplete_expired';
    public const STRIPE_STATUS_TRIALING           = 'trialing';
    use Id;
    use IsDisabled;
    use MajorVersion;
    use LicenseKey;
    use UserNotes;
    use AdminNotes;
    use AuthorizedDomains;
    use ExpiresAt;
    use User;
    use Software;
    use StripeStatus;
    use StripeSubscriptionId;
    use StripeSubscriptionItemId;
    use StripeCanceledAt;
    use StripeSubscriptionAmount;

    public static function fromRecord(LicenseRecord $record): self
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return new self(
            id: $record->getId(),
            isDisabled: $record->getIsDisabled(),
            majorVersion: $record->getMajorVersion(),
            licenseKey: $record->getLicenseKey(),
            userNotes: $record->getUserNotes(),
            adminNotes: $record->getAdminNotes(),
            authorizedDomains: $record->getAuthorizedDomains(),
            expiresAt: $record->getExpiresAt(),
            user: UserEntity::fromRecord(record: $record->getUser()),
            software: SoftwareEntity::fromRecord(record: $record->getSoftware()),
            stripeStatus: $record->getStripeStatus(),
            stripeSubscriptionId: $record->getStripeSubscriptionId(),
            stripeSubscriptionItemId: $record->getStripeSubscriptionItemId(),
            stripeCanceledAt: $record->getStripeCanceledAt(),
            stripeSubscriptionAmount: $record->getStripeSubscriptionAmount(),
        );
    }

    /**
     * @param string[] $authorizedDomains
     */
    public function __construct(
        bool $isDisabled = false,
        string $majorVersion = '',
        string $licenseKey = '',
        string $userNotes = '',
        string $adminNotes = '',
        array $authorizedDomains = [],
        null | string | DateTimeInterface $expiresAt = null,
        ?UserEntity $user = null,
        ?SoftwareEntity $software = null,
        string $stripeStatus = '',
        string $stripeSubscriptionId = '',
        string $stripeSubscriptionItemId = '',
        int | Money $stripeSubscriptionAmount = 0,
        null | string | DateTimeInterface $stripeCanceledAt = null,
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

        $this->licenseKey = $licenseKey;

        $this->userNotes = $userNotes;

        $this->adminNotes = $adminNotes;

        $this->authorizedDomains = $authorizedDomains;

        $this->expiresAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $expiresAt,
        );

        $this->user = $user;

        $this->software = $software;

        $this->stripeStatus = $stripeStatus;

        $this->stripeSubscriptionId = $stripeSubscriptionId;

        $this->stripeSubscriptionItemId = $stripeSubscriptionItemId;

        $this->stripeCanceledAt = DateTimeUtility::createDateTimeImmutableOrNull(
            $stripeCanceledAt,
        );

        if ($stripeSubscriptionAmount instanceof Money) {
            $this->stripeSubscriptionAmount = $stripeSubscriptionAmount;
        } else {
            $this->stripeSubscriptionAmount = new Money(
                $stripeSubscriptionAmount,
                new Currency('USD')
            );
        }
    }

    private bool $isInitialized = false;

    public function adminLink(): string
    {
        return '/' . implode(
            '/',
            [
                'admin',
                'users',
                $this->userGuarantee()->emailAddress(),
                'licenses',
                $this->licenseKey(),
            ],
        );
    }

    public function adminCancelSubscriptionLink(): string
    {
        return implode(
            '/',
            [
                $this->adminLink(),
                'cancel-subscription',
            ]
        );
    }

    public function adminResumeSubscriptionLink(): string
    {
        return implode(
            '/',
            [
                $this->adminLink(),
                'resume-subscription',
            ]
        );
    }

    public function adminAddAuthorizedDomainLink(): string
    {
        return implode(
            '/',
            [
                $this->adminLink(),
                'add-authorized-domain',
            ],
        );
    }

    public function adminDeleteAuthorizedDomainLink(string $domain): string
    {
        return implode(
            '/',
            [
                $this->adminLink(),
                'delete-authorized-domain',
                $domain,
            ],
        );
    }

    public function adminEditAdminNotesLink(): string
    {
        return implode(
            '/',
            [
                $this->adminLink(),
                'edit-admin-notes',
            ],
        );
    }

    public function adminDisableLicenseLink(): string
    {
        return implode(
            '/',
            [
                $this->adminLink(),
                'disable-license',
            ],
        );
    }

    public function adminEnableLicenseLink(): string
    {
        return implode(
            '/',
            [
                $this->adminLink(),
                'enable-license',
            ],
        );
    }

    public function accountLink(): string
    {
        return '/' . implode(
            '/',
            [
                'account',
                'licenses',
                $this->licenseKey(),
            ],
        );
    }

    public function accountAddAuthorizedDomainLink(): string
    {
        return implode(
            '/',
            [
                $this->accountLink(),
                'add-authorized-domain',
            ],
        );
    }

    public function accountEditNotesLink(): string
    {
        return implode(
            '/',
            [
                $this->accountLink(),
                'edit-notes',
            ],
        );
    }

    public function accountDeleteAuthorizedDomainLink(string $domain): string
    {
        return implode(
            '/',
            [
                $this->accountLink(),
                'delete-authorized-domain',
                $domain,
            ],
        );
    }

    public function accountCancelSubscriptionLink(): string
    {
        return implode(
            '/',
            [
                $this->accountLink(),
                'cancel-subscription',
            ],
        );
    }

    public function accountResumeSubscriptionLink(): string
    {
        return implode(
            '/',
            [
                $this->accountLink(),
                'resume-subscription',
            ],
        );
    }

    public function accountStartNewSubscriptionLink(): string
    {
        return implode(
            '/',
            [
                $this->accountLink(),
                'start-new-subscription',
            ],
        );
    }

    public function renewalDate(): ?DateTimeImmutable
    {
        $expiresAt = $this->expiresAt();

        if ($expiresAt === null) {
            return null;
        }

        return $expiresAt;
    }

    public function isExpired(): bool
    {
        $currentDateTime = new DateTimeImmutable();

        return $currentDateTime > $this->expiresAt() || $this->isNotActive();
    }

    public function isNotExpired(): bool
    {
        return ! $this->isExpired();
    }

    public function isSubscription(): bool
    {
        return $this->expiresAt !== null;
    }

    public function isNotSubscription(): bool
    {
        return ! $this->isSubscription();
    }

    public function isActive(): bool
    {
        if (! $this->isSubscription()) {
            return true;
        }

        return $this->stripeStatus === self::STRIPE_STATUS_ACTIVE;
    }

    public function isNotActive(): bool
    {
        return ! $this->isActive();
    }

    public function isCanceled(): bool
    {
        return $this->stripeCanceledAt() !== null;
    }

    public function isNotCanceled(): bool
    {
        return $this->stripeCanceledAt() === null;
    }
}

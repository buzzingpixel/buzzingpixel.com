<?php

declare(strict_types=1);

namespace App\Context\Users\Entities;

use App\Context\Users\Exceptions\InvalidEmailAddress;
use App\Context\Users\Exceptions\InvalidPassword;
use App\EntityPropertyTraits\CreatedAt;
use App\EntityPropertyTraits\EmailAddress;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\IsActive;
use App\EntityPropertyTraits\IsAdmin;
use App\EntityPropertyTraits\PasswordHash;
use App\EntityPropertyTraits\Timezone;
use App\EntityPropertyTraits\UserStripeId;
use App\EntityValueObjects\EmailAddress as EmailAddressValue;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Users\UserRecord;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use LogicException;

use function assert;
use function implode;
use function is_string;
use function password_hash;

use const PASSWORD_DEFAULT;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class User
{
    use Id;
    use UserStripeId;
    use IsAdmin;
    use EmailAddress;
    use PasswordHash;
    use IsActive;
    use Timezone;
    use CreatedAt;

    private UserSupportProfile $supportProfile;
    private UserBillingProfile $billingProfile;

    /**
     * @throws InvalidEmailAddress
     * @throws InvalidPassword
     */
    public static function fromRecord(UserRecord $record): self
    {
        return new self(
            id: $record->getId()->toString(),
            userStripeId: $record->getUserStripeId(),
            isAdmin: $record->getIsAdmin(),
            emailAddress: $record->getEmailAddress(),
            passwordHash: $record->getPasswordHash(),
            isActive: $record->getIsActive(),
            timezone: $record->getTimezone(),
            createdAt: $record->getCreatedAt(),
            supportProfile: UserSupportProfile::fromRecord(
                $record->getSupportProfile(),
            ),
            billingProfile: UserBillingProfile::fromRecord(
                $record->getBillingProfile(),
            ),
        );
    }

    /**
     * @throws InvalidEmailAddress
     * @throws InvalidPassword
     */
    public function __construct(
        string $emailAddress,
        string $passwordHash = '',
        bool $isAdmin = false,
        bool $isActive = true,
        null | string | DateTimeZone $timezone = null,
        null | string | DateTimeInterface $createdAt = null,
        ?string $id = null,
        string $userStripeId = '',
        string $plainTextPassword = '',
        ?UserSupportProfile $supportProfile = null,
        ?UserBillingProfile $billingProfile = null,
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'This object can only be constructed once'
            );
        }

        if ($id === null) {
            $this->id = IdValue::create();
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->userStripeId = $userStripeId;

        $this->isAdmin = $isAdmin;

        $this->emailAddress = EmailAddressValue::fromString(
            $emailAddress
        );

        if ($plainTextPassword !== '') {
            $this->validatePassword($plainTextPassword);

            /** @phpstan-ignore-next-line */
            $this->passwordHash = (string) password_hash(
                $plainTextPassword,
                PASSWORD_DEFAULT,
            );
        } elseif ($passwordHash !== '') {
            $this->passwordHash = $passwordHash;
        }

        $this->isActive = $isActive;

        if ($timezone === null) {
            $this->timezone = new DateTimeZone('US/Central');
        } elseif ($timezone instanceof DateTimeZone) {
            $this->timezone = $timezone;
        } else {
            $this->timezone = new DateTimeZone($timezone);
        }

        if ($createdAt instanceof DateTimeInterface) {
            $createdAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $createdAt->format(DateTimeInterface::ATOM),
            );
        } elseif (is_string($createdAt)) {
            $createdAtClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $createdAt,
            );
        } else {
            $createdAtClass = new DateTimeImmutable();
        }

        assert($createdAtClass instanceof DateTimeImmutable);

        $createdAtClass = $createdAtClass->setTimezone(
            new DateTimeZone('UTC')
        );

        $this->createdAt = $createdAtClass;

        if ($supportProfile !== null) {
            $this->supportProfile = $supportProfile;
        } else {
            $this->supportProfile = new UserSupportProfile();
        }

        if ($billingProfile !== null) {
            $this->billingProfile = $billingProfile;
        } else {
            $this->billingProfile = new UserBillingProfile();
        }

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    public function supportProfile(): UserSupportProfile
    {
        return $this->supportProfile;
    }

    public function withSupportProfile(UserSupportProfile $supportProfile): self
    {
        $clone = clone $this;

        $clone->supportProfile = $supportProfile;

        return $clone;
    }

    public function billingProfile(): UserBillingProfile
    {
        return $this->billingProfile;
    }

    public function withBillingProfile(UserBillingProfile $billingProfile): self
    {
        $clone = clone $this;

        $clone->billingProfile = $billingProfile;

        return $clone;
    }

    public function adminBaseLink(): string
    {
        return '/' . implode(
            '/',
            [
                'admin',
                'users',
                $this->emailAddress(),
            ],
        );
    }

    public function adminLogInAsLink(): string
    {
        return implode(
            '/',
            [
                $this->adminBaseLink(),
                'log-in-as',
            ]
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

    private bool $isSyncingWithStripe = false;

    public function isSyncingWithStripe(): bool
    {
        return $this->isSyncingWithStripe;
    }

    public function withIsSyncingWithStripe(
        bool $isSyncingWithStripe = true
    ): self {
        $clone = clone $this;

        $clone->isSyncingWithStripe = $isSyncingWithStripe;

        return $clone;
    }
}

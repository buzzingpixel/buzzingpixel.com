<?php

declare(strict_types=1);

namespace App\Context\Software\Entities;

use App\EntityPropertyTraits\DownloadFile;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\MajorVersion;
use App\EntityPropertyTraits\NewFileLocation;
use App\EntityPropertyTraits\ReleasedOn;
use App\EntityPropertyTraits\UpgradePrice;
use App\EntityPropertyTraits\Version;
use App\EntityValueObjects\Id as IdValue;
use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\Entities\Software\SoftwareVersionRecord;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use LogicException;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

use function assert;
use function implode;
use function is_string;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class SoftwareVersion
{
    use Id;
    use MajorVersion;
    use Version;
    use DownloadFile;
    use NewFileLocation;
    use UpgradePrice;
    use ReleasedOn;

    private ?Software $software = null;

    public static function fromRecord(
        SoftwareVersionRecord $record,
        ?Software $software = null,
    ): self {
        if ($software === null) {
            $softwareRecord = $record->getSoftware();

            assert($softwareRecord instanceof SoftwareRecord);

            $software = Software::fromRecord($softwareRecord);
        }

        return new self(
            id: $record->getId(),
            majorVersion: $record->getMajorVersion(),
            version: $record->getVersion(),
            downloadFile: $record->getDownloadFile(),
            upgradePrice: $record->getUpgradePrice(),
            releasedOn: $record->getReleasedOn(),
            software: $software,
        );
    }

    public function __construct(
        string $majorVersion = '',
        string $version = '',
        string $downloadFile = '',
        string $newFileLocation = '',
        int | Money $upgradePrice = 0,
        null | string | DateTimeInterface $releasedOn = null,
        ?Software $software = null,
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

        $this->majorVersion = $majorVersion;

        $this->version = $version;

        $this->downloadFile = $downloadFile;

        $this->newFileLocation = $newFileLocation;

        if ($upgradePrice instanceof Money) {
            $this->upgradePrice = $upgradePrice;
        } else {
            $this->upgradePrice = new Money(
                $upgradePrice,
                new Currency('USD')
            );
        }

        if ($releasedOn instanceof DateTimeInterface) {
            $releasedOnClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $releasedOn->format(DateTimeInterface::ATOM),
            );
        } elseif (is_string($releasedOn)) {
            $releasedOnClass = DateTimeImmutable::createFromFormat(
                DateTimeInterface::ATOM,
                $releasedOn,
            );
        } else {
            $releasedOnClass = new DateTimeImmutable();
        }

        assert($releasedOnClass instanceof DateTimeImmutable);

        $releasedOnClass = $releasedOnClass->setTimezone(
            new DateTimeZone('UTC'),
        );

        $this->releasedOn = $releasedOnClass;

        $this->software = $software;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    public function software(): ?Software
    {
        return $this->software;
    }

    public function softwareGuarantee(): Software
    {
        $software = $this->software();

        assert($software instanceof Software);

        return $software;
    }

    public function withSoftware(Software $software): self
    {
        $clone = clone $this;

        $clone->software = $software;

        return $clone;
    }

    public function name(): string
    {
        $software = $this->software();

        assert($software instanceof Software);

        return $software->name() . ' ' . $this->version();
    }

    public function adminBaseLink(): string
    {
        $software = $this->software();

        assert($software instanceof Software);

        return '/' . implode(
            '/',
            [
                'admin',
                'software',
                $software->slug(),
                'version',
                $this->version(),
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
}

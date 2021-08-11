<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services;

use App\Context\Licenses\Contracts\LicenseValidity;
use App\Context\Licenses\Contracts\SaveLicense;
use App\Context\Licenses\Entities\License;
use App\Payload\Payload;
use App\Persistence\Entities\Licenses\LicenseRecord;
use Psr\Log\LoggerInterface;

use function implode;

class SaveLicenseInvalid implements SaveLicense
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    /** @psalm-suppress PropertyNotSetInConstructor */
    private LicenseValidity $validity;

    public function withValidity(LicenseValidity $validity): self
    {
        $clone = clone $this;

        $clone->validity = $validity;

        return $clone;
    }

    public function save(
        License $license,
        ?LicenseRecord $licenseRecord = null
    ): Payload {
        $this->logger->error(
            'The License entity is invalid',
            [
                'licenseEntity' => $license,
                'licenseValidity' => $this->validity,
            ],
        );

        return new Payload(
            status: $this->validity->payloadStatusText(),
            result: [
                'message' => implode(
                    ' ',
                    $this->validity->validationErrors(),
                ),
            ],
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Licenses\Downloads\Factories;

use App\Context\Licenses\Entities\License;
use App\Context\Software\Entities\SoftwareVersion;

class VersionFromLicenseFactory
{
    public function getVersion(?License $license): ?SoftwareVersion
    {
        if ($license === null) {
            return null;
        }

        $software = $license->softwareGuarantee();

        $mostRecentVersion = $software->versions()->first();

        if ($license->isNotSubscription() || $license->isNotExpired()) {
            return $mostRecentVersion;
        }

        $maxVersion = $software->versions()->where(
            'version',
            $license->maxVersion(),
        )->firstOrNull();

        if ($maxVersion === null || $maxVersion->downloadFile() === '') {
            return null;
        }

        return $maxVersion;
    }
}

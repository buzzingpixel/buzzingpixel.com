<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\CheckLicenseStatus\Factories;

use App\Context\Licenses\Entities\License;
use App\Context\Licenses\Services\CheckLicenseStatus\Entities\CheckLicenseResult;
use App\Context\Licenses\Services\CheckLicenseStatus\Services\IsDevDomain;
use App\Context\Url\Entities\Url;

use function array_merge;
use function in_array;
use function version_compare;

class CheckLicenseResultFactory
{
    public function __construct(private IsDevDomain $isDevDomain)
    {
    }

    public function getResult(
        Url $domain,
        string $version,
        ?License $license,
        string $softwareSlug,
    ): CheckLicenseResult {
        // Validate the license
        if ($license === null) {
            return new CheckLicenseResult(
                isValid: false,
                reason: 'License key not found',
            );
        }

        $software = $license->softwareGuarantee();

        $slugs = array_merge(
            [$software->slug()],
            $software->bundledSoftware(),
        );

        // Validate the software of the license
        if (! in_array($softwareSlug, $slugs, true)) {
            return new CheckLicenseResult(
                isValid: false,
                reason: 'License is not for specified software',
            );
        }

        // Check if this is a subscription, and if it is, if we've passed the
        // specified version (in the case of expired or canceled subscriptions)
        if (
            $version !== '' &&
            $license->isSubscription() &&
            $license->isExpired()
        ) {
            $incomingVersionIsGreater = version_compare(
                $version,
                $license->maxVersion(),
                '>'
            );

            if ($incomingVersionIsGreater) {
                return new CheckLicenseResult(
                    isValid: false,
                    reason: 'Version is newer than subscription allows',
                );
            }
        }

        // Now if this is not a dev domain, we should check against the domains
        if (! $this->isDevDomain->check($domain)) {
            $matchedADomain = false;

            foreach ($license->authorizedDomains() as $authorizedDomain) {
                $authorizedParsed = Url::createFromString($authorizedDomain);

                if ($authorizedParsed->getPrimaryDomain() !== $domain->getPrimaryDomain()) {
                    continue;
                }

                $matchedADomain = true;

                break;
            }

            if ($matchedADomain === false) {
                return new CheckLicenseResult(
                    isValid: false,
                    reason: 'Domain did not match any authorized domains for license',
                );
            }
        }

        return new CheckLicenseResult(isValid: true);
    }
}

<?php

declare(strict_types=1);

namespace App\Context\Licenses\Services\CheckLicenseStatus\Services;

use App\Context\Url\Entities\Url;
use Config\General;

use function in_array;
use function ip2long;

class IsDevDomain
{
    public function __construct(private General $config)
    {
    }

    public function check(Url $domain): bool
    {
        // If there's no TLD, this is a dev domain
        if ($domain->getTld() === '') {
            return true;
        }

        // Check if the TLD is dev-y
        if (
            in_array(
                $domain->getTld(),
                $this->config->devSubDomains(),
                true
            )
        ) {
            return true;
        }

        // Check if subdomain parts are dev-y
        foreach ($domain->getSubDomainParts() as $subDomainPart) {
            if (
                in_array(
                    $subDomainPart,
                    $this->config->devSubDomains(),
                    true
                )
            ) {
                return true;
            }
        }

        // Check if this is an IP address
        if (ip2long((string) $domain->getHost()) !== false) {
            return true;
        }

        // Check if the port is not 80 or 443
        return $domain->getPort() !== null &&
            $domain->getPort() !== 80 &&
            $domain->getPort() !== 443;
    }
}

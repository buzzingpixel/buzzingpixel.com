<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Licenses\LicenseListing;

use Psr\Http\Message\ResponseInterface;

interface LicenseListingResponderContract
{
    public function respond(): ResponseInterface;
}

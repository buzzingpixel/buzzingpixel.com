<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex\Responder;

use App\Payload\Payload;

class PostNewLicenseResponderFactory
{
    public function __construct(
        private PostNewLicenseResponderValid $postNewLicenseResponderValid,
        private PostNewLicenseResponderInvalid $postNewLicenseResponderInvalid,
    ) {
    }

    public function create(Payload $payload): PostNewLicenseResponderContract
    {
        if ($payload->getStatus() !== Payload::STATUS_CREATED) {
            return $this->postNewLicenseResponderInvalid;
        }

        return $this->postNewLicenseResponderValid;
    }
}

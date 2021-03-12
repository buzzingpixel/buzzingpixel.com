<?php

declare(strict_types=1);

namespace App\Context\Email\Services;

use App\Context\Email\Entity\EmailRecipient;
use Config\General;

class GetDefaultEmailSender
{
    public function __construct(private General $config)
    {
    }

    public function get(): EmailRecipient
    {
        return new EmailRecipient(
            emailAddress: $this->config->systemEmailSenderAddress(),
            name: $this->config->systemEmailSenderName(),
        );
    }
}

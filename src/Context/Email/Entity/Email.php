<?php

declare(strict_types=1);

namespace App\Context\Email\Entity;

class Email
{
    /** @phpstan-ignore-next-line */
    public function __construct(
        private EmailRecipient $from,
        /** @phpstan-ignore-next-line */
        private EmailRecipientCollection $recipients,
        private string $subject,
        private string $plaintext = '',
        private string $html = '',
    ) {
    }

    public function from(): EmailRecipient
    {
        return $this->from;
    }

    /** @phpstan-ignore-next-line */
    public function recipients(): EmailRecipientCollection
    {
        return $this->recipients;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function plaintext(): string
    {
        return $this->plaintext;
    }

    public function html(): string
    {
        return $this->html;
    }
}

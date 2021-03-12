<?php

declare(strict_types=1);

use App\Context\Email\Adapters\MailerSendAdapter;
use App\Context\Email\Interfaces\SendMailAdapter;
use MailerSend\MailerSend;

use function DI\autowire;

return [
    SendMailAdapter::class => autowire(MailerSendAdapter::class),
    MailerSend::class => static function (): MailerSend {
        return new MailerSend(
            /** @phpstan-ignore-next-line */
            ['api_key' => (string) getenv('MAILER_SEND_API_KEY')],
        );
    },
];

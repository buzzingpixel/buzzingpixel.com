<?php

declare(strict_types=1);

use MailerSend\MailerSend;

return [
    MailerSend::class => static function (): MailerSend {
        /** @phpstan-ignore-next-line */
        $apiKey = (string) getenv('MAILER_SEND_API_KEY');

        $debug = (bool) getenv('DEV_MODE');

        return new MailerSend([
            'api_key' => $apiKey,
            'debug' => $debug,
        ]);
    },
];

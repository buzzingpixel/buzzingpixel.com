<?php

declare(strict_types=1);

namespace App\Context\Email\Services;

use App\Context\Email\Entity\Email;
use App\Context\Email\Interfaces\SendMailAdapter;
use App\Payload\Payload;
use Exception;
use Throwable;

use function assert;
use function unserialize;

use const PHP_EOL;

class SendQueueEmail
{
    public function __construct(private SendMailAdapter $sendMail)
    {
    }

    /**
     * @param array<string, string> $context
     *
     * @throws Throwable
     */
    public function send(array $context): void
    {
        $email = unserialize($context['email']);

        assert($email instanceof Email);

        $payload = $this->sendMail->send($email);

        if ($payload->getStatus() === Payload::STATUS_SUCCESSFUL) {
            return;
        }

        /** @psalm-suppress MixedOperand */
        throw new Exception(
            message: 'Status: ' . $payload->getStatus() . PHP_EOL .
            'Message: ' . $payload->getResult()['message'] . PHP_EOL .
            'Error message: ' . $payload->getResult()['errorMessage'],
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Context\Email;

use App\Context\Email\Entity\Email;
use App\Context\Email\Interfaces\SendMailAdapter;
use App\Context\Email\Services\QueueEmail;
use App\Payload\Payload;

class EmailApi
{
    public function __construct(
        private SendMailAdapter $sendMail,
        private QueueEmail $queueEmail,
    ) {
    }

    public function sendMail(Email $email): Payload
    {
        return $this->sendMail->send($email);
    }

    public function queueEmail(Email $email): void
    {
        $this->queueEmail->queue($email);
    }
}

<?php

declare(strict_types=1);

namespace App\Context\Email\Interfaces;

use App\Context\Email\Entity\Email;
use App\Payload\Payload;

interface SendMailAdapter
{
    public function send(Email $email): Payload;
}

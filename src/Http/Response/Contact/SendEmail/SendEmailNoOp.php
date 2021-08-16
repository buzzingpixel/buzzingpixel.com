<?php

declare(strict_types=1);

namespace App\Http\Response\Contact\SendEmail;

use App\Http\Response\Contact\Entities\FormValues;

class SendEmailNoOp implements SendEmailContract
{
    public function send(FormValues $formValues): void
    {
    }
}

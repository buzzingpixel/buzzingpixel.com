<?php

declare(strict_types=1);

namespace App\Http\Response\Contact\SendEmail;

use App\Http\Response\Contact\Entities\FormValues;

class SendEmailFactory
{
    public function __construct(
        private SendEmailNoOp $sendEmailNoOp,
        private SendEmailSend $sendEmailSend,
    ) {
    }

    public function getSendEmail(FormValues $formValues): SendEmailContract
    {
        if ($formValues->isNotValid()) {
            return $this->sendEmailNoOp;
        }

        return $this->sendEmailSend;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Response\Contact\Responder;

use App\Http\Response\Contact\Entities\FormValues;

class PostContactResponderFactory
{
    public function __construct(
        private PostContactResponderValid $responderValid,
        private PostContactResponderInvalid $responderInvalid,
    ) {
    }

    public function getResponder(FormValues $formValues): PostContactResponderContract
    {
        if ($formValues->isNotValid()) {
            return $this->responderInvalid;
        }

        return $this->responderValid;
    }
}

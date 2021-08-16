<?php

declare(strict_types=1);

namespace App\Http\Response\Contact\Responder;

use App\Http\Response\Contact\Entities\FormValues;
use Psr\Http\Message\ResponseInterface;

interface PostContactResponderContract
{
    public function respond(FormValues $formValues): ResponseInterface;
}

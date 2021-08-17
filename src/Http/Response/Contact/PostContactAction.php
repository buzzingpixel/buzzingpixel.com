<?php

declare(strict_types=1);

namespace App\Http\Response\Contact;

use App\Http\Response\Contact\Entities\FormValues;
use App\Http\Response\Contact\Responder\PostContactResponderFactory;
use App\Http\Response\Contact\SendEmail\SendEmailFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_array;

class PostContactAction
{
    public function __construct(
        private SendEmailFactory $sendEmailFactory,
        private PostContactResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $requestData = $request->getParsedBody();

        assert(is_array($requestData));

        $formValues = new FormValues(
            name: (string) ($requestData['your_name'] ?? ''),
            email: (string) ($requestData['your_email'] ?? ''),
            message: (string) ($requestData['message'] ?? ''),
        );

        $this->sendEmailFactory
            ->getSendEmail(formValues: $formValues)
            ->send(formValues: $formValues);

        return $this->responderFactory
            ->getResponder(formValues: $formValues)
            ->respond(formValues: $formValues);
    }
}

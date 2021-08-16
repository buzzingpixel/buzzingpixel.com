<?php

declare(strict_types=1);

namespace App\Http\Response\Contact\SendEmail;

use App\Context\Email\EmailApi;
use App\Context\Email\Entity\Email;
use App\Http\Response\Contact\Entities\FormValues;
use Config\General;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SendEmailSend implements SendEmailContract
{
    public function __construct(
        private General $config,
        private EmailApi $emailApi,
        private TwigEnvironment $twig,
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function send(FormValues $formValues): void
    {
        $twigContext = [
            'name' => $formValues->name(),
            'emailAddress' => $formValues->email(),
            'message' => $formValues->message(),
        ];

        $this->emailApi->queueEmail(
            email: new Email(
                subject: 'BuzzingPixel Contact Form',
                recipients: $this->config->contactFormRecipients(),
                plaintext: $this->twig->render(
                    '@app/Http/Response/Contact/SendEmail/EmailTemplatePlainText.twig',
                    $twigContext,
                ),
                html: $this->twig->render(
                    '@app/Http/Response/Contact/SendEmail/EmailTemplateHtml.twig',
                    $twigContext,
                ),
            ),
        );
    }
}

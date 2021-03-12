<?php

declare(strict_types=1);

namespace App\Context\Email\Adapters;

use App\Context\Email\Entity\Email;
use App\Context\Email\Entity\EmailRecipient;
use App\Context\Email\Interfaces\SendMailAdapter;
use App\Context\Email\Services\GetDefaultEmailSender;
use App\Payload\Payload;
use cebe\markdown\GithubMarkdown;
use Config\General;
use Html2Text\Html2Text;
use JsonException;
use LogicException;
use MailerSend\Exceptions\MailerSendAssertException;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\MailerSend;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_map;

class MailerSendAdapter implements SendMailAdapter
{
    /**
     * @see https://github.com/mailersend/mailersend-php
     */
    public function __construct(
        private GithubMarkdown $markdown,
        private MailerSend $mailerSend,
        private LoggerInterface $logger,
        private General $config,
        private GetDefaultEmailSender $getDefaultEmailSender,
    ) {
    }

    public function send(Email $email): Payload
    {
        try {
            return $this->innerSend($email);
        } catch (Throwable $exception) {
            if ($this->config->devMode()) {
                throw $exception;
            }

            $this->logger->emergency(
                'An exception was caught sending an email',
                ['exception' => $exception],
            );

            return new Payload(
                Payload::STATUS_ERROR,
                [
                    'message' => 'There was a problem sending the email',
                    'errorMessage' => $exception->getMessage(),
                ]
            );
        }
    }

    /**
     * @throws JsonException
     * @throws MailerSendAssertException
     * @throws ClientExceptionInterface
     */
    public function innerSend(Email $email): Payload
    {
        $from = $email->from();

        if ($from === null) {
            $from = $this->getDefaultEmailSender->get();
        }

        $recipients = array_map(
            static fn (EmailRecipient $person) => new Recipient(
                $person->emailAddress(),
                $person->name(),
            ),
            $email->recipients()->toArray(),
        );

        $html = $email->html();

        $plainText = $email->plaintext();

        if ($html === '' && $plainText === '') {
            throw new LogicException(
                'Either HTML or Plain Text must be provided',
            );
        }

        if ($plainText === '') {
            $plainText = (new Html2Text($email->html()))->getText();
        }

        if ($html === '') {
            $html = $this->markdown->parse($plainText);
        }

        $emailParams = (new EmailParams())
            ->setFrom($from->emailAddress())
            ->setRecipients($recipients)
            ->setSubject($email->subject())
            ->setHtml($html)
            ->setText($plainText);

        $fromName = $from->name();

        if ($fromName !== null) {
            $emailParams->setFromName($fromName);
        }

        $this->mailerSend->email->send($emailParams);

        return new Payload(
            Payload::STATUS_SUCCESSFUL,
            ['message' => 'The email has been sent']
        );
    }
}

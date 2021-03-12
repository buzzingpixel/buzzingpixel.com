<?php

declare(strict_types=1);

namespace App\Context\Users\Services;

use App\Context\Email\EmailApi;
use App\Context\Email\Entity\Email;
use App\Context\Email\Entity\EmailRecipient;
use App\Context\Email\Entity\EmailRecipientCollection;
use App\Context\Users\Entities\User;
use Config\General;
use Twig\Environment as TwigEnvironment;

class RequestPasswordResetEmail
{
    public function __construct(
        private GeneratePasswordResetToken $generatePasswordResetToken,
        private TwigEnvironment $twig,
        private General $config,
        private EmailApi $emailApi,
    ) {
    }

    public function request(User $user): void
    {
        $token = $this->generatePasswordResetToken->generate($user);

        if ($token === null) {
            return;
        }

        $this->emailApi->queueEmail(
            email: new Email(
                subject: 'Reset your' . $this->config->siteName() . 'password',
                recipients: new EmailRecipientCollection([
                    new EmailRecipient(emailAddress: $user->emailAddress()),
                ]),
                plaintext: $this->twig->render(
                    '@app/Context/Users/TwigTemplates/PasswordResetEmail.twig',
                    [
                        'emailAddress' => $user->emailAddress(),
                        'link' => $this->config->siteUrl() .
                            '/reset-pw-with-token/' .
                            $token->id(),
                    ]
                ),
            ),
        );
    }
}

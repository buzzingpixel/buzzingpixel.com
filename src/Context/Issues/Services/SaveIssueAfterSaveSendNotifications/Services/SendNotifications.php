<?php

declare(strict_types=1);

namespace App\Context\Issues\Services\SaveIssueAfterSaveSendNotifications\Services;

use App\Context\Email\EmailApi;
use App\Context\Email\Entity\Email;
use App\Context\Email\Entity\EmailRecipient;
use App\Context\Email\Entity\EmailRecipientCollection;
use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueSubscriber;
use App\Context\Issues\Services\SaveIssueAfterSaveSendNotifications\Contracts\SendNotificationsContract;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Response\Support\IssueListing\Services\IssueLinkResolver;
use Config\General;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SendNotifications implements SendNotificationsContract
{
    public function __construct(
        private General $config,
        private EmailApi $emailApi,
        private TwigEnvironment $twig,
        private LoggedInUser $loggedInUser,
        private IssueLinkResolver $issueLinkResolver,
    ) {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function send(Issue $issue, bool $wasNew = false): void
    {
        $desc = $issue->shortDescription();

        $loggedInId = $this->loggedInUser->user()->id();

        $subscribersToSend = $issue->issueSubscribers()->filter(
            static fn (
                IssueSubscriber $i,
            ) => $i->userGuarantee()->id() !== $loggedInId,
        );

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $this->emailApi->queueEmail(
            email: new Email(
                subject: 'BuzzingPixel Issue Update - ' . $desc,
                recipients: new EmailRecipientCollection(
                    $subscribersToSend->mapToArray(
                        static fn (
                            IssueSubscriber $i,
                        ) => new EmailRecipient(
                            emailAddress: $i->userGuarantee()->emailAddress(),
                        ),
                    ),
                ),
                from: $this->config->noReplyRecipient(),
                plaintext: $this->twig->render(
                    '@app/Context/Issues/Services/SaveIssueAfterSaveSendNotifications/Templates/IssueNotification.twig',
                    [
                        'issue' => $issue,
                        'wasNew' => $wasNew,
                        'linkResolver' => $this->issueLinkResolver,
                    ],
                ),
            ),
        );
    }
}

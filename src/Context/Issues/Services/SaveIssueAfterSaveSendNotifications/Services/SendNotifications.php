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

    public function send(Issue $issue, bool $wasNew = false): void
    {
        $loggedInId = $this->loggedInUser->user()->id();

        $subscribersToSend = $issue->issueSubscribers()
            ->filter(
                static fn (
                    IssueSubscriber $i,
                ) => $i->userGuarantee()->id() !== $loggedInId,
            )
            ->filter(
                static fn (
                    IssueSubscriber $i
                ) => $i->isActive() === true,
            );

        $subscribersToSend->map(
            function (IssueSubscriber $subscriber) use (
                $issue,
                $wasNew,
            ): void {
                $desc = $issue->shortDescription();

                $this->emailApi->queueEmail(
                    email: new Email(
                        subject: 'BuzzingPixel Issue Update - ' . $desc,
                        recipients: new EmailRecipientCollection(
                            [
                                new EmailRecipient(
                                    $subscriber
                                        ->userGuarantee()
                                        ->emailAddress(),
                                ),
                            ],
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
        );
    }
}

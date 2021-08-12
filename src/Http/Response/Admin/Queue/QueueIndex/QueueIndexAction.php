<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Queue\QueueIndex;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\QueueApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Context\Users\Entities\User;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;

class QueueIndexAction
{
    private User $currentUser;

    public function __construct(
        private General $config,
        private QueueApi $queueApi,
        LoggedInUser $loggedInUser,
        private TwigEnvironment $twig,
        private ResponseFactoryInterface $responseFactory,
    ) {
        $this->currentUser = $loggedInUser->user();
    }

    public function __invoke(): ResponseInterface
    {
        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['queue']['isActive'] = true;

        $upcomingQueues = $this->queueApi->fetchNextXQueues();

        $lastFinishedQueues = $this->queueApi->fetchLastXFinishedQueues();

        $lastErrorQueues = $this->queueApi->fetchLastXErrorQueues();

        $response = $this->responseFactory->createResponse();

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminMultiKeyValue.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Queue | Admin',
                ),
                'accountMenu' => $adminMenu,
                'keyValueCards' => [
                    [
                        'headline' => 'Upcoming Queues',
                        'items' => $upcomingQueues->count() > 0 ?
                            $upcomingQueues->mapToArray(
                                fn (Queue $q) => $this->formatDisplay(
                                    queue : $q
                                ),
                            ) :
                            [
                                [
                                    'key' => 'No upcoming queues',
                                    'value' => '',
                                ],
                            ],
                    ],
                    [
                        'headline' => 'Last Finished Queues',
                        'items' => $lastFinishedQueues->count() > 0 ?
                            $lastFinishedQueues->mapToArray(
                                fn (Queue $q) => $this->formatDisplay(
                                    queue : $q
                                ),
                            ) :
                            [
                                [
                                    'key' => 'Last finished queues',
                                    'value' => '',
                                ],
                            ],
                    ],
                    [
                        'headline' => 'Last Errored Queues',
                        'items' => $lastErrorQueues->count() > 0 ?
                            $lastErrorQueues->mapToArray(
                                fn (Queue $q) => $this->formatDisplay(
                                    queue : $q
                                ),
                            ) :
                            [
                                [
                                    'key' => 'Last finished queues',
                                    'value' => '',
                                ],
                            ],
                    ],
                ],
            ],
        ));

        return $response;
    }

    /**
     * @return mixed[]
     */
    private function formatDisplay(Queue $queue): array
    {
        $dateFormat = 'F jS, Y, g:is a';

        $finishedAt = $queue->finishedAt();

        $errorMsg = (string) $queue->errorMessage();

        if ($errorMsg !== '') {
            $errorMsg = '<div class="prose"><pre><code>' .
                $errorMsg .
                '</code></pre></div>';
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return [
            'key' => $queue->addedAt()->setTimezone(
                $this->currentUser->timezone()
            )->format($dateFormat),
            'value' => $this->twig->render(
                'Http/_Infrastructure/Display/KeyValueCard.twig',
                [
                    'noVerticalMargin' => true,
                    'items' => [
                        [
                            'key' => 'Handle',
                            'value' => $queue->handle(),
                        ],
                        [
                            'key' => 'Has Started',
                            'value' => $queue->hasStarted() ? 'Yes' : 'No',
                        ],
                        [
                            'key' => 'Is Running',
                            'value' => $queue->isRunning() ? 'Yes' : 'No',
                        ],
                        [
                            'key' => 'Assume Dead After',
                            'value' => $queue->assumeDeadAfter()->setTimezone(
                                $this->currentUser->timezone()
                            )->format($dateFormat),
                        ],
                        [
                            'key' => 'Initial Assume Dead After',
                            'value' => $queue->initialAssumeDeadAfter()->setTimezone(
                                $this->currentUser->timezone()
                            )->format($dateFormat),
                        ],
                        [
                            'key' => 'Is Finished',
                            'value' => $queue->isFinished() ? 'Yes' : 'No',
                        ],
                        [
                            'key' => 'Finished Due To Error',
                            'value' => $queue->finishedDueToError() ? 'Yes' : 'No',
                        ],
                        [
                            'key' => 'Error Message',
                            'value' => $errorMsg,
                        ],
                        [
                            'key' => 'Percent Complete',
                            'value' => (string) $queue->percentComplete(),
                        ],
                        [
                            'key' => 'Finished At',
                            'value' => $finishedAt !== null ?
                                $finishedAt->setTimezone(
                                    $this->currentUser->timezone()
                                )->format($dateFormat) :
                                '',
                        ],
                    ],
                ]
            ),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Response\Stripe\Webhook;

use App\Context\Queue\Entities\Queue;
use App\Context\Queue\Entities\QueueItem;
use App\Context\Queue\QueueApi;
use App\Context\Stripe\QueueActions\SyncCustomerStripeItemsQueueAction;
use App\Context\Users\Entities\User;
use App\Context\Users\UserApi;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Throwable;
use UnexpectedValueException;

use function assert;
use function json_encode;

class PostCheckoutSessionCompletedAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private General $config,
        private UserApi $userApi,
        private QueueApi $queueApi,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        try {
            return $this->innerRun($request);
        } catch (UnexpectedValueException $e) {
            $response = $this->responseFactory->createResponse(400);

            $response->getBody()->write((string) json_encode([
                'success' => false,
                'type' => 'Unexpected value',
                'error' => $e->getMessage(),
            ]));

            return $response;
        } catch (SignatureVerificationException $e) {
            $response = $this->responseFactory->createResponse(400);

            $response->getBody()->write((string) json_encode([
                'success' => false,
                'type' => 'Invalid signature',
                'error' => $e->getMessage(),
            ]));

            return $response;
        } catch (Throwable $e) {
            $response = $this->responseFactory->createResponse(500);

            $response->getBody()->write((string) json_encode([
                'success' => false,
                'type' => 'Unexpected server error',
                'error' => $e->getMessage(),
            ]));

            return $response;
        }
    }

    /**
     * @throws SignatureVerificationException
     */
    public function innerRun(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getServerParams();

        $event = Webhook::constructEvent(
            $request->getBody()->getContents(),
            (string) ($params['HTTP_STRIPE_SIGNATURE'] ?? ''),
            $this->config->stripeCheckoutSessionCompletedSigningSecret(),
        );

        assert($event->type === 'checkout.session.completed');

        $checkoutSession = $event->data['object'];

        assert($checkoutSession instanceof Session);

        $user = $this->userApi->fetchOneUser(
            (new UserQueryBuilder())
                ->withUserStripeId((string) $checkoutSession->customer)
        );

        assert($user instanceof User);

        $this->queueApi->addToQueue(
            (new Queue())
                ->withHandle('sync-user-stripe-items')
                ->withAddedQueueItem(
                    newQueueItem: new QueueItem(
                        className: SyncCustomerStripeItemsQueueAction::class,
                        methodName: 'sync',
                        context: ['userId' => $user->id()],
                    ),
                ),
        );

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write(
            (string) json_encode(['status' => 'ok'])
        );

        return $response;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Purchases;

use App\Context\Orders\OrderApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;

class AccountPurchasesAction
{
    public function __construct(
        private General $config,
        private OrderApi $orderApi,
        private TwigEnvironment $twig,
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        $accountMenu = $this->config->accountMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $accountMenu['purchases']['isActive'] = true;

        $orders = $this->orderApi->fetchOrders(
            (new OrderQueryBuilder())
                ->withUserId($this->loggedInUser->user()->id())
                ->withOrderBy('orderDate', 'desc'),
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write(
            string: $this->twig->render(
                name: '@app/Http/Response/Account/Purchases/AccountPurchases.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Purchases | Account',
                    ),
                    'accountMenu' => $accountMenu,
                    'headline' => 'Purchases',
                    'orders' => $orders,
                ],
            ),
        );

        return $response;
    }
}

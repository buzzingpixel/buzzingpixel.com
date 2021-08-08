<?php

declare(strict_types=1);

namespace App\Http\Response\Account\Purchases;

use App\Context\Orders\Entities\OrderItem;
use App\Context\Orders\OrderApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;

class AccountPurchasesDetailAction
{
    public function __construct(
        private General $config,
        private OrderApi $orderApi,
        private TwigEnvironment $twig,
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $orderId = (string) $request->getAttribute('orderId');

        $order = $this->orderApi->fetchOneOrder(
            (new OrderQueryBuilder())
                ->withUserId($this->loggedInUser->user()->id())
                ->withId($orderId),
        );

        if ($order === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $accountMenu = $this->config->accountMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $accountMenu['purchases']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write(
            string: $this->twig->render(
                name: '@app/Http/Response/Account/AccountKeyValuePage.twig',
                context: [
                    'meta' => new Meta(
                        metaTitle: 'Viewing Order | Account | Admin',
                    ),
                    'accountMenu' => $accountMenu,
                    'breadcrumbSingle' => [
                        'content' => 'Purchases',
                        'uri' => '/account/purchases',
                    ],
                    'breadcrumbTrail' => [
                        [
                            'content' => 'Account',
                            'uri' => '/account',
                        ],
                        [
                            'content' => 'Purchases',
                            'uri' => '/account/purchases',
                        ],
                        ['content' => 'View'],
                    ],
                    'keyValueCard' => [
                        'headline' => 'Order ID: ' . $order->id(),
                        'subHeadline' => 'Order Total: ' . $order->totalFormatted(),
                        'items' => [
                            [
                                'key' => 'Order ID',
                                'value' => $order->id(),
                            ],
                            [
                                'key' => 'Subtotal',
                                'value' => $order->subTotalFormatted(),
                            ],
                            [
                                'key' => 'Tax',
                                'value' => $order->taxFormatted(),
                            ],
                            [
                                'key' => 'Total',
                                'value' => $order->totalFormatted(),
                            ],
                            [
                                'template' => 'Http/_Infrastructure/Display/SimpleTableList.twig',
                                'key' => $order->orderItems()->count() > 1 ?
                                    'Order Items' :
                                    'Order Item',
                                'value' => [
                                    'items' => $order->orderItems()->mapToArray(
                                        static function (OrderItem $item): array {
                                            $content = $item->software()->name();

                                            $content .= '<br>' . $item->priceFormatted();

                                            if ($item->quantity() > 1) {
                                                $content .= ' x ' . $item->quantity();
                                            }

                                            $content .= '</small>';

                                            return [
                                                'content' => $content,
                                                'links' => [
                                                    [
                                                        'href' => $item->license()->accountLink(),
                                                        'content' => 'License',
                                                    ],
                                                    [
                                                        'href' => $item->software()->pageLink(),
                                                        'content' => 'Software Page',
                                                    ],
                                                ],
                                            ];
                                        },
                                    ),
                                ],
                            ],
                        ],
                    ],
                ],
            ),
        );

        return $response;
    }
}

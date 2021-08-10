<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\View;

use App\Context\Orders\Entities\OrderItem;
use App\Context\Orders\OrderApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Context\Users\UserApi;
use App\Http\Entities\Meta;
use App\Http\Response\Admin\Users\UserConfig;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use DateTimeImmutable;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;

use function assert;

class ViewUserPurchaseDetailAction
{
    public function __construct(
        private General $config,
        private UserApi $userApi,
        private OrderApi $orderApi,
        private TwigEnvironment $twig,
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $emailAddress = (string) $request->getAttribute('emailAddress');

        $user = $this->userApi->fetchOneUser(
            (new UserQueryBuilder())
                ->withEmailAddress($emailAddress),
        );

        if ($user === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $orderId = (string) $request->getAttribute('orderId');

        $order = $this->orderApi->fetchOneOrder(
            queryBuilder: (new OrderQueryBuilder())
                ->withId($orderId)
                ->withUserId($user->id()),
        );

        if ($order === null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new HttpNotFoundException($request);
        }

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['users']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        $orderDate = $order->orderDate();

        assert($orderDate instanceof DateTimeImmutable);

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminKeyValuePage.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Viewing Order | Purchases ' . $user->emailAddress() . ' | Users | Admin',
                ),
                'accountMenu' => $adminMenu,
                'tabs' => UserConfig::getUserViewTabs(
                    baseAdminProfileLink: $user->adminBaseLink(),
                    activeTab: 'purchases',
                ),
                'breadcrumbSingle' => [
                    'content' => 'Purchases',
                    'uri' => '/admin/users/' . $user->emailAddress() . '/purchases',
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Admin',
                        'uri' => '/admin',
                    ],
                    [
                        'content' => 'Users',
                        'uri' => '/admin/users',
                    ],
                    [
                        'content' => 'Profile',
                        'uri' => '/admin/users/' . $user->emailAddress(),
                    ],
                    [
                        'content' => 'Purchases',
                        'uri' => '/admin/users/' . $user->emailAddress() . '/purchases',
                    ],
                    ['content' => 'Order'],
                ],
                'keyValueCard' => [
                    'headline' => 'Order ID: ' . $order->id() . ' | ' . $user->emailAddress(),
                    'subHeadline' => 'Order Total: ' . $order->totalFormatted(),
                    'items' => [
                        [
                            'key' => 'Order ID',
                            'value' => $order->id(),
                        ],
                        [
                            'key' => 'Order Date',
                            'value' => $orderDate->setTimezone(
                                $this->loggedInUser->user()->timezone(),
                            )->format('F jS, Y, g:i a'),
                        ],
                        [
                            'key' => 'Old Order Number',
                            'value' => $order->oldOrderNumber(),
                        ],
                        [
                            'key' => 'Stripe ID',
                            'value' => $order->stripeId(),
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
                                                    'href' => $item->license()->adminLink(),
                                                    'content' => 'License',
                                                ],
                                                [
                                                    'href' => $item->software()->adminBaseLink(),
                                                    'content' => 'Software',
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
        ));

        return $response;
    }
}

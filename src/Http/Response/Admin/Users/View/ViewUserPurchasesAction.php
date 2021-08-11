<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\View;

use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderItem;
use App\Context\Orders\OrderApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Context\Users\UserApi;
use App\Http\Entities\Meta;
use App\Http\Response\Admin\Users\UserConfig;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;

use function implode;

class ViewUserPurchasesAction
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

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['users']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminStackedListTwoColumn.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Purchases ' . $user->emailAddress() . ' | Users | Admin',
                ),
                'accountMenu' => $adminMenu,
                'tabs' => UserConfig::getUserViewTabs(
                    baseAdminProfileLink: $user->adminBaseLink(),
                    activeTab: 'purchases',
                ),
                'breadcrumbSingle' => [
                    'content' => 'Profile',
                    'uri' => '/admin/users/' . $user->emailAddress(),
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
                    ['content' => 'Purchases'],
                ],
                'stackedListTwoColumnConfig' => [
                    'headline' => 'Purchases by ' . $user->emailAddress(),
                    'noResultsContent' => 'There are no orders from this user yet.',
                    'items' => $this->orderApi->fetchOrders(
                        queryBuilder: (new OrderQueryBuilder())
                            ->withUserId($user->id())
                            ->withOrderBy('orderDate', 'desc'),
                    )->mapToArray(
                        function (Order $order): array {
                            $date = $order->orderDate();

                            return [
                                'href' => $order->adminBaseLink(),
                                'column1Headline' => $order->id(),
                                'column1SubHeadline' => $date === null ? '' :
                                    $date->setTimezone(
                                        $this->loggedInUser->user()->timezone(),
                                    )->format('F j, Y'),
                                'column2Headline' => $order->totalFormatted(),
                                'column2SubHeadline' => implode(
                                    ', ',
                                    $order->orderItems()->mapToArray(
                                        static fn (OrderItem $i) => $i
                                            ->software()->name(),
                                    ),
                                ),
                            ];
                        }
                    ),
                ],
            ],
        ));

        return $response;
    }
}

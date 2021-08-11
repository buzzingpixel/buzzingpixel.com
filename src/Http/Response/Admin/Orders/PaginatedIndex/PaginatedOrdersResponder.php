<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Orders\PaginatedIndex;

use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderCollection;
use App\Context\Orders\Entities\OrderItem;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Http\Entities\Pagination;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function implode;

class PaginatedOrdersResponder implements PaginatedOrdersResponderContract
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @phpstan-ignore-next-line
     */
    public function respond(
        Pagination $pagination,
        OrderCollection $orders,
    ): ResponseInterface {
        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['orders']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminStackedListTwoColumn.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Orders | Admin',
                ),
                'accountMenu' => $adminMenu,
                'stackedListTwoColumnConfig' => [
                    'pagination' => $pagination,
                    'headline' => 'Orders',
                    'noResultsContent' => 'There are no orders yet.',
                    'items' => $orders->mapToArray(
                        function (Order $order): array {
                            $date = $order->orderDate();

                            return [
                                'href' => $order->adminBaseLink(),
                                'column1Headline' => $order->id(),
                                'column1SubHeadline' => $date === null ? '' :
                                    $date->setTimezone(
                                        $this->loggedInUser->user()
                                            ->timezone(),
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
                        },
                    ),
                ],
            ]
        ));

        return $response;
    }
}

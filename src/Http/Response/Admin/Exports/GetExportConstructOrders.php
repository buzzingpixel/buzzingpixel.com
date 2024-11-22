<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Exports;

use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderItem;
use App\Context\Orders\OrderApi;
use App\Persistence\QueryBuilders\Orders\OrderQueryBuilder;
use League\Csv\Writer as CsvWriter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function implode;
use function in_array;
use function mb_strlen;

class GetExportConstructOrders
{
    public function __construct(private OrderApi $orderApi)
    {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $orderCollection = $this->orderApi->fetchOrders(
            queryBuilder: new OrderQueryBuilder(),
        );

        $constructOrders = $orderCollection->filter(
            static function (Order $order): bool {
                $isConstruct = false;

                foreach ($order->orderItems()->toArray() as $orderItem) {
                    if (
                        ! in_array(
                            $orderItem->software()->slug(),
                            [
                                'construct',
                                'category-construct',
                            ]
                        )
                    ) {
                        continue;
                    }

                    $isConstruct = true;

                    break;
                }

                return $isConstruct;
            }
        );

        $csv = CsvWriter::createFromString();

        $csv->insertOne([
            'ID',
            'emailAddress',
            'subTotal',
            'tax',
            'total',
            'billingName',
            'billingCompany',
            'billingPhone',
            'billingCountryRegion',
            'billingAddress',
            'billingAddressContinued',
            'billingCity',
            'billingStateProvince',
            'billingPostalCode',
            'orderDate',
            'item:price',
        ]);

        $constructOrders->map(
            static function (Order $order) use ($csv): void {
                $orderDate = $order->orderDate();

                if ($orderDate === null) {
                    $orderDate = '';
                } else {
                    $orderDate = $orderDate->getTimestamp();
                }

                $csv->insertOne([
                    $order->id(),
                    $order->user()->emailAddress(),
                    $order->subTotalAsInt(),
                    $order->taxAsInt(),
                    $order->totalAsInt(),
                    $order->billingName(),
                    $order->billingCompany(),
                    $order->billingPhone(),
                    $order->billingCountryRegion(),
                    $order->billingAddress(),
                    $order->billingAddressContinued(),
                    $order->billingCity(),
                    $order->billingStateProvince(),
                    $order->billingPostalCode(),
                    $orderDate,
                    implode(',', $order->orderItems()->mapToArray(
                        static function (OrderItem $item): string {
                            return $item->software()->slug() . ':' . $item->priceAsInt();
                        }
                    )),
                ]);
            }
        );

        $csvString = $csv->toString();

        $response->getBody()->write($csvString);

        return $response->withHeader(
            'Content-Disposition',
            'attachment; filename="construct-orders.csv"'
        )
            ->withHeader('Content-Type', 'text/plain')
            ->withHeader('Content-Length', mb_strlen($csvString))
            ->withHeader('Connection', 'close');
    }
}

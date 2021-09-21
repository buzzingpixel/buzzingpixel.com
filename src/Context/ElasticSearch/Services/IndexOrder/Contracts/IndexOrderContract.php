<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexOrder\Contracts;

use App\Context\Orders\Entities\Order;

interface IndexOrderContract
{
    public function indexOrder(Order $order): void;
}

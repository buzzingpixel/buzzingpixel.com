<?php

declare(strict_types=1);

namespace App\Context\Orders\Factories;

use App\Context\Orders\Contracts\OrderValidity;
use App\Context\Orders\Entities\Order;
use App\Context\Orders\Entities\OrderValidityIsInvalid;
use App\Context\Orders\Entities\OrderValidityIsValid;

use function array_values;
use function count;

class OrderValidityFactory
{
    public function createOrderValidity(Order $order): OrderValidity
    {
        $validationErrors = [];

        if ($order->user() === null) {
            $validationErrors[] = 'An order must have a user.';
        }

        foreach ($order->orderItems()->toArray() as $orderItem) {
            if (! $orderItem->hasLicense()) {
                $validationErrors['license'] = 'An order item must have a license.';
            }

            if ($orderItem->hasSoftware()) {
                continue;
            }

            $validationErrors['software'] = 'An order item must have a software assignment.';
        }

        if (count($validationErrors) > 0) {
            return new OrderValidityIsInvalid(
                validationErrors: array_values($validationErrors),
            );
        }

        return new OrderValidityIsValid();
    }
}

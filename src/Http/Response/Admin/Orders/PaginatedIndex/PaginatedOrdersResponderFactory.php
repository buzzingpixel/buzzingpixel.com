<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Orders\PaginatedIndex;

use App\Context\Users\Entities\LoggedInUser;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment as TwigEnvironment;

class PaginatedOrdersResponderFactory
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private LoggedInUser $loggedInUser,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function make(
        OrderResult $orderResult,
        ServerRequestInterface $request,
    ): PaginatedOrdersResponderContract {
        $pageNum = $orderResult->pagination()->currentPage();

        $orders = $orderResult->orders();

        if ($pageNum > 1 && $orders->count() < 1) {
            return new PaginatedOrdersResponderInvalid(request: $request);
        }

        return new PaginatedOrdersResponder(
            twig: $this->twig,
            config: $this->config,
            orderResult: $orderResult,
            loggedInUser: $this->loggedInUser,
            responseFactory: $this->responseFactory,
        );
    }
}

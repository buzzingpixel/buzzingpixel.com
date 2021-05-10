<?php

declare(strict_types=1);

namespace App\Http\Response\Cart;

use App\Context\Cart\CartApi;
use App\Context\Cart\Entities\CartItem;
use App\Context\Cart\Entities\CurrentUserCart;
use App\Context\Software\SoftwareApi;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class AddToCartAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private CurrentUserCart $currentUserCart,
        private CartApi $cartApi,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $softwareSlug = (string) $request->getAttribute('softwareSlug');

        $software = $this->softwareApi->fetchOneSoftware(
            (new SoftwareQueryBuilder())
                ->withSlug($softwareSlug),
        );

        if ($software === null || ! $software->isForSale()) {
            throw new HttpNotFoundException($request);
        }

        $cart = $this->currentUserCart->cart()->withAddedCartItem(
            new CartItem(
                software: $software,
                quantity: 1,
            ),
        );

        $this->cartApi->saveCart($cart);

        return $this->responseFactory->createResponse(303)
            ->withHeader('Location', '/cart');
    }
}

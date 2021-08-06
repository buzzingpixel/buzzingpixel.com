<?php

declare(strict_types=1);

namespace App\Http\Response\Ajax\Cart;

use App\Context\Cart\CartApi;
use App\Context\Cart\Entities\CartItem;
use App\Context\Cart\Entities\CurrentUserCart;
use App\Context\Software\SoftwareApi;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use LogicException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function json_decode;
use function json_encode;

class UpdateCartAction
{
    public function __construct(
        private CurrentUserCart $userCart,
        private CartApi $cartApi,
        private SoftwareApi $softwareApi,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * Get posted cart data
         *
         * @var array<string, array> $postedCartData
         */
        $postedCartData = (array) json_decode(
            $request->getBody()->getContents(),
            true,
        );

        $cart = $this->userCart->cart();

        // Create an array of existing cart items keyed by slug so we can get
        // them by slug
        $existingCartItemsBySlug = [];

        foreach ($cart->cartItems()->toArray() as $item) {
            $existingCartItemsBySlug[$item->slug()] = $item;
        }

        // We'll put our updated cart items here
        $updatedItems = [];

        foreach ($postedCartData as $data) {
            $slug = (string) ($data['slug'] ?? '');

            $quantity = (int) ($data['quantity'] ?? 0);

            if ($quantity < 1) {
                continue;
            }

            $cartItem = $existingCartItemsBySlug[$slug] ?? null;

            if ($cartItem === null) {
                $software = $this->softwareApi->fetchOneSoftware(
                    (new SoftwareQueryBuilder())
                        ->withSlug($slug),
                );

                if ($software === null) {
                    throw new LogicException(
                        'Unable to locate specified software'
                    );
                }

                $cartItem = new CartItem(
                    software: $software,
                    quantity: $quantity,
                );
            }

            $cartItem = $cartItem->withQuantity($quantity);

            $updatedItems[] = $cartItem;
        }

        $cart = $cart->withCartItems($updatedItems);

        $this->cartApi->saveCart($cart);

        $response = $this->responseFactory->createResponse()
            ->withHeader(
                'Content-type',
                'application/json',
            );

        $response->getBody()->write((string) json_encode([
            'totalItems' => $cart->totalItems(),
            'subTotal' => $cart->subTotalFormatted(),
            'tax' => $cart->taxFormatted(),
            'total' => $cart->totalFormatted(),
            'hasMoreThanOneSubscription' => $cart->hasMoreThanOneSubscription(),
        ]));

        return $response;
    }
}

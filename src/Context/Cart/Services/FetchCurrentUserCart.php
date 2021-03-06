<?php

declare(strict_types=1);

namespace App\Context\Cart\Services;

use App\Context\Cart\Entities\Cart;
use App\Context\Users\Entities\LoggedInUser;
use App\Persistence\QueryBuilders\Cart\CartQueryBuilder;
use buzzingpixel\cookieapi\interfaces\CookieApiInterface;
use DateTimeImmutable;
use DateTimeZone;

use function assert;
use function strtotime;

class FetchCurrentUserCart
{
    public function __construct(
        private LoggedInUser $loggedInUser,
        private CookieApiInterface $cookieApi,
        private FetchOneCart $fetchOneCart,
        private SaveCart $saveCart,
    ) {
    }

    public function fetch(): Cart
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $currentDate = new DateTimeImmutable(
            'now',
            new DateTimeZone('UTC')
        );

        $currentDatePlus20Years = $currentDate->setTimestamp(
            strtotime('+ 20 years')
        );

        $currentDateMinus1Minute = $currentDate->setTimestamp(
            strtotime('- 1 minute')
        );

        $cookie = $this->cookieApi->retrieveCookie('cart_id');

        $cookieCart = $cookie !== null ?
            $this->fetchOneCart->fetch(
                (new CartQueryBuilder())
                    ->withId($cookie->value()),
            ) :
            null;

        $userCart = $this->loggedInUser->hasUser() ?
            $this->fetchOneCart->fetch(
                (new CartQueryBuilder())
                    ->withUserId($this->loggedInUser->user()->id()),
            ) :
            null;

        if ($cookieCart !== null && $userCart !== null) {
            foreach ($cookieCart->cartItems()->toArray() as $item) {
                $userCart = $userCart->withAddedCartItem($item);
            }
        }

        if ($this->loggedInUser->hasUser() && $userCart === null) {
            if ($cookieCart !== null) {
                $userCart = $cookieCart->withUser(
                    $this->loggedInUser->user()
                );
            } else {
                $userCart = new Cart(
                    $this->loggedInUser->user()
                );
            }

            /**
             * Psalm needs the assert for... stupid reasons... who knows
             *
             * @phpstan-ignore-next-line
             */
            assert($userCart instanceof Cart);

            /** @psalm-suppress MixedAssignment */
            $userCart = $this->saveCart->save($userCart)
                ->getResult()['cartEntity'];

            assert($userCart instanceof Cart);
        }

        if (! $this->loggedInUser->hasUser() && $cookieCart === null) {
            $cookieCart = new Cart();

            $this->saveCart->save($cookieCart);

            $this->cookieApi->saveCookie(
                $this->cookieApi->makeCookie(
                    'cart_id',
                    $cookieCart->id(),
                    $currentDatePlus20Years,
                )
            );
        }

        if ($userCart !== null && $cookie !== null) {
            // The cookie api delete cookie function is broken. Need to fix that
            // $this->cookieApi->deleteCookie($cookie);
            $this->cookieApi->saveCookie(
                $this->cookieApi->makeCookie(
                    'cart_id',
                    '',
                    $currentDateMinus1Minute,
                )
            );
        }

        $cart = $userCart ?? $cookieCart;

        assert($cart instanceof Cart);

        $twentyFourHoursInSeconds = 86400;

        $lastTouchedTimestamp = $cart->lastTouchedAt()->getTimestamp();

        $lastTouchedDiff = $currentDate->getTimestamp() - $lastTouchedTimestamp;

        // Update last touched at if it's been more than 24 hours since last
        // touched at was updated
        if ($lastTouchedDiff > $twentyFourHoursInSeconds) {
            /** @psalm-suppress MixedAssignment */
            $cart = $this->saveCart->save($cart)
                ->getResult()['cartEntity'];

            assert($cart instanceof Cart);
        }

        return $cart;
    }
}

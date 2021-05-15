<?php

declare(strict_types=1);

namespace App\Http\Response\Cart;

use App\Context\Cart\Entities\CurrentUserCart;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;

class CartAction
{
    private const ACTION_PAY_NOW                 = 'payNow';
    private const ACTION_LOG_IN_TO_PAY           = 'logInToPay';
    private const ACTION_COMPLETE_PROFILE_TO_PAY = 'completeProfileToPay';

    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private CurrentUserCart $currentUserCart,
        private LoggedInUser $loggedInUser,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        $actionButton = self::ACTION_PAY_NOW;

        if (! $this->loggedInUser->hasUser()) {
            $actionButton = self::ACTION_LOG_IN_TO_PAY;
        } elseif (! $this->loggedInUser->user()->billingProfile()->isComplete()) {
            $actionButton = self::ACTION_COMPLETE_PROFILE_TO_PAY;
        }

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Cart/Cart.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Cart',
                ),
                'cart' => $this->currentUserCart->cart(),
                'actionButton' => $actionButton,
            ],
        ));

        return $response;
    }
}

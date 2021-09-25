<?php

declare(strict_types=1);

namespace App\Http\AppMiddleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Csrf\Guard as CsrfGuard;

use function in_array;

class CsrfGuardMiddleware implements MiddlewareInterface
{
    public const EXCLUDE = ['/stripe/webhook/checkout-session-completed'];

    public function __construct(private CsrfGuard $csrfGuard)
    {
    }

    /**
     * @throws Exception
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $disableCsrf = in_array(
            $request->getUri()->getPath(),
            self::EXCLUDE,
            true,
        );

        if ($disableCsrf) {
            return $handler->handle($request);
        }

        return $this->csrfGuard->process(
            $request,
            $handler,
        );
    }
}

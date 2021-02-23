<?php

declare(strict_types=1);

namespace App\Http\Response\Error;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HttpErrorAction
{
    private Error404Responder $error404Responder;
    private Error500Responder $error500Responder;

    public function __construct(
        Error404Responder $error404Responder,
        Error500Responder $error500Responder
    ) {
        $this->error404Responder = $error404Responder;
        $this->error500Responder = $error500Responder;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception
    ): ResponseInterface {
        if ($exception instanceof HttpNotFoundException) {
            return ($this->error404Responder)();
        }

        return ($this->error500Responder)(exception: $exception);
    }
}

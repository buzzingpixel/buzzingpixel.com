<?php

declare(strict_types=1);

namespace App\Templating\TwigExtensions;

use Slim\Flash\Messages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use function session_status;

use const PHP_SESSION_ACTIVE;

class TwigSlimFlashMessages extends AbstractExtension
{
    public function __construct(protected Messages $flash)
    {
    }

    public static function shouldAddExtension(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash', [$this, 'getMessages']),
        ];
    }

    public function getMessages(?string $key = null): mixed
    {
        if ($key !== null) {
            return $this->flash->getMessage($key);
        }

        return $this->flash->getMessages();
    }
}

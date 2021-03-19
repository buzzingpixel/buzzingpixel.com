<?php

declare(strict_types=1);

namespace App\Templating\TwigExtensions;

use Slim\Flash\Messages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigSlimFlashMessages extends AbstractExtension
{
    protected Messages $flash;

    public function __construct(Messages $flash)
    {
        $this->flash = $flash;
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

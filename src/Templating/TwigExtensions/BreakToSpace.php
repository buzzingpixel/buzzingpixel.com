<?php

declare(strict_types=1);

namespace App\Templating\TwigExtensions;

use Throwable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use function Safe\preg_replace;
use function trim;

class BreakToSpace extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [$this->getFunction()];
    }

    private function getFunction(): TwigFunction
    {
        return new TwigFunction(
            'breakToSpace',
            [$this, 'breakToSpaceMethod']
        );
    }

    /**
     * @throws Throwable
     */
    public function breakToSpaceMethod(string $classes): string
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        return trim(preg_replace(
            '/\s\s+/',
            ' ',
            $classes
        ));
    }
}

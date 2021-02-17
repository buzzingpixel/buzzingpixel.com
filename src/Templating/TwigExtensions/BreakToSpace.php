<?php

declare(strict_types=1);

namespace App\Templating\TwigExtensions;

use Throwable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use function assert;
use function is_string;
use function Safe\preg_replace;
use function trim;

class BreakToSpace extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function getFunctions(): array
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
        $string = preg_replace(
            '/\s\s+/',
            ' ',
            $classes
        );

        assert(is_string($string));

        return trim($string);
    }
}

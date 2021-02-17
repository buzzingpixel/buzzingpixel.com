<?php

declare(strict_types=1);

namespace App\Templating\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\Loader\LoaderInterface;
use Twig\TwigFunction;

class TemplateExists extends AbstractExtension
{
    private LoaderInterface $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

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
            'templateExists',
            [$this, 'templateExists']
        );
    }

    public function templateExists(string $templatePath): bool
    {
        return $this->loader->exists($templatePath);
    }
}

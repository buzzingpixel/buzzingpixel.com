<?php

declare(strict_types=1);

namespace App\Templating\TwigExtensions;

use Config\General;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SiteUrl extends AbstractExtension
{
    public function __construct(private General $config)
    {
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
            'siteUrl',
            [$this, 'siteUrl']
        );
    }

    public function siteUrl(string $uri = ''): string
    {
        return $this->config->siteUrl() . $uri;
    }
}

<?php

declare(strict_types=1);

namespace App\Templating\TwigExtensions;

use League\ISO3166\ISO3166 as ISO3166Source;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ISO3166 extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'ISO3166All',
                [$this, 'all'],
            ),
            new TwigFunction(
                'ISO3166Alpha2',
                [$this, 'alpha2'],
            ),
            new TwigFunction(
                'ISO3166Alpha3',
                [$this, 'alpha3'],
            ),
        ];
    }

    /**
     * @return mixed[]
     */
    public function all(): array
    {
        return (new ISO3166Source())->all();
    }

    /**
     * @return mixed[]
     */
    public function alpha2(string $alpha2): array
    {
        return (new ISO3166Source())->alpha2($alpha2);
    }

    /**
     * @return mixed[]
     */
    public function alpha3(string $alpha3): array
    {
        return (new ISO3166Source())->alpha3($alpha3);
    }
}

<?php

declare(strict_types=1);

namespace App\Templating\TwigExtensions;

use Cdtweb\UsStatesList;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UsStates extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'UsStatesAll',
                [$this, 'all'],
            ),
            new TwigFunction(
                'UsStatesAbbreviations',
                [$this, 'abbreviations'],
            ),
            new TwigFunction(
                'UsStatesNames',
                [$this, 'names'],
            ),
        ];
    }

    /**
     * @return mixed[]
     */
    public function all(): array
    {
        return UsStatesList::all();
    }

    /**
     * @return mixed[]
     */
    public function abbreviations(): array
    {
        return UsStatesList::abbreviations();
    }

    /**
     * @return mixed[]
     */
    public function names(): array
    {
        return UsStatesList::names();
    }
}

<?php

declare(strict_types=1);

namespace App\Templating\TwigExtensions;

use Jawira\CaseConverter\CaseConverterException;
use Jawira\CaseConverter\Convert;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;

class CaseConverter extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function getFilters()
    {
        return [
            new TwigFilter(
                'toCamelCase',
                [$this, 'toCamelCase'],
            ),
        ];
    }

    /**
     * @throws CaseConverterException
     */
    public function toCamelCase(string $str): Markup
    {
        return new Markup(
            (new Convert($str))->toCamel(),
            'UTF-8'
        );
    }
}

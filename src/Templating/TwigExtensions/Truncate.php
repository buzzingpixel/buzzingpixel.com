<?php

declare(strict_types=1);

namespace App\Templating\TwigExtensions;

use Exception;
use TS\Text\Truncation;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;

class Truncate extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'truncate',
                [$this, 'truncateFilter'],
            ),
        ];
    }

    /**
     * @throws Exception
     */
    public function truncateFilter(
        string $val,
        int $limit,
        string $strategy = 'word',
        string $truncationString = '…',
        int $minLength = 0,
    ): Markup {
        $strategies = [
            'char' => Truncation::STRATEGY_CHARACTER,
            'line' => Truncation::STRATEGY_LINE,
            'paragraph' => Truncation::STRATEGY_PARAGRAPH,
            'sentence' => Truncation::STRATEGY_SENTENCE,
            'word' => Truncation::STRATEGY_WORD,
        ];

        $truncation = new Truncation(
            $limit,
            $strategies[$strategy],
            $truncationString,
            'UTF-8',
            $minLength,
        );

        return new Markup(
            $truncation->truncate($val),
            'UTF-8',
        );
    }
}

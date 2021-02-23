<?php

declare(strict_types=1);

namespace App\Content\Changelog;

use MJErwin\ParseAChangelog\Release as ErwinRelease;

use function count;
use function in_array;
use function is_array;

class Release extends ErwinRelease
{
    public const MESSAGE_TYPES = [
        'added',
        'changed',
        'deprecated',
        'removed',
        'fixed',
        'security',
    ];

    /**
     * TODO: Excluding this from code coverage for now.
     *
     * @return array<array<string>>
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function getMessageTypesContent(): array
    {
        $arr = [];

        /** @psalm-suppress MixedAssignment */
        foreach ($this->toArray() as $key => $item) {
            if (
                ! in_array($key, self::MESSAGE_TYPES, true) ||
                ! is_array($item) ||
                count($item) < 1
            ) {
                continue;
            }

            /** @psalm-suppress MixedAssignment */
            $arr[$key] = $item;
        }

        /** @psalm-suppress MixedReturnTypeCoercion */
        return $arr;
    }

    /**
     * Decided not to do this for now
     */
    // public function toHtml() : string
    // {
    //     $contentString = implode("\n", $this->content);
    //
    //     // Oh man I hate this
    //     $parser = new GithubMarkdown();
    //
    //     $parser->enableNewlines = true;
    //     $parser->html5          = true;
    //
    //     return $parser->parse($contentString);
    // }
}

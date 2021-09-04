<?php

declare(strict_types=1);

namespace App\Http\Utilities\Minify;

use Minify_HTML;

class Minifier
{
    public function __invoke(string $html): string
    {
        $options = [
            'cssMinifier' => '\Minify_CSSmin::minify',
            'jsMinifier' => '\JSMin\JSMin::minify',
        ];

        return (new Minify_HTML($html, $options))->process();
    }
}

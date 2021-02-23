<?php

declare(strict_types=1);

namespace App\Content\Changelog;

use MJErwin\ParseAChangelog\Reader as ErwinReader;

use function array_slice;
use function assert;
use function count;
use function current;
use function is_array;
use function key;
use function mb_strpos;
use function next;
use function preg_grep;

class Reader extends ErwinReader
{
    // phpcs:disable
    /** @var array<int, string> */
    protected $content;

    /** @var array<string, Release> */
    protected $releases = [];
    // phpcs:enable

    /**
     * @return array<string, Release>
     */
    public function getReleases(): array
    {
        if (count($this->releases) === 0) {
            $contentArrayCount = count($this->content);

            $contentArrayLastKey = $contentArrayCount - 1;

            $newContent = [];

            foreach ($this->content as $key => $val) {
                $newContent[] = $val;

                if ($key === $contentArrayLastKey || $val === '') {
                    continue;
                }

                $next = $this->content[$key + 1];

                if ($key === 0 || mb_strpos($next, '###') !== 0) {
                    continue;
                }

                $newContent[] = '';
            }

            $this->content = $newContent;

            $headings = preg_grep(
                '/^## (\[?)([^\s\[\]#]*)(\]?)( - ([0-9]{4}-[0-9]{2}-[0-9]{2}))?$/',
                $this->content
            );

            assert(is_array($headings));

            while ($currentHeading = (string) current($headings)) {
                $start = (int) key($headings);
                next($headings);
                $end = key($headings);

                if ($end !== null) {
                    $end  = (int) $end;
                    $end -= $start;
                }

                $releaseContent = array_slice(
                    $this->content,
                    $start,
                    $end
                );

                $release = new Release($releaseContent);

                $this->releases[$release->getVersion()] = $release;
            }
        }

        return $this->releases;
    }
}

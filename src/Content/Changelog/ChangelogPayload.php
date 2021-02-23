<?php

declare(strict_types=1);

namespace App\Content\Changelog;

use function array_slice;
use function array_walk;

class ChangelogPayload
{
    /** @var Release[] */
    private array $releases = [];

    /**
     * @param Release[] $releases
     */
    public function __construct(array $releases = [])
    {
        array_walk($releases, [$this, 'addRelease']);
    }

    protected function addRelease(Release $release): void
    {
        $this->releases[] = $release;
    }

    /**
     * @return Release[]
     */
    public function getReleases(): array
    {
        return $this->releases;
    }

    public function withReleaseSlice(
        int $length,
        int $offset = 0
    ): ChangelogPayload {
        return new ChangelogPayload(array_slice(
            $this->releases,
            $offset,
            $length
        ));
    }
}

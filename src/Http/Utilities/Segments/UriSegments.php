<?php

declare(strict_types=1);

namespace App\Http\Utilities\Segments;

use InvalidArgumentException;
use LogicException;

use function array_slice;
use function array_values;
use function count;
use function implode;
use function max;

class UriSegments
{
    private bool $isInitialized = false;

    /** @var array<int, string> */
    private array $segments;
    /** @var array<int, string> */
    private array $segmentsSansPagination;
    private int $pageNum;
    private bool $hasPaginationTrigger;

    /**
     * @param array<int, string> $segments
     * @param array<int, string> $segmentsSansPagination
     */
    public function __construct(
        array $segments,
        array $segmentsSansPagination,
        int $pageNum,
        bool $hasPaginationTrigger
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'UriSegments can only be initialized once'
            );
        }

        $this->segments               = array_values($segments);
        $this->segmentsSansPagination = array_values($segmentsSansPagination);
        $this->pageNum                = $pageNum;
        $this->hasPaginationTrigger   = $hasPaginationTrigger;

        $this->isInitialized = true;
    }

    /**
     * @return array<int, string>
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    public function getSegment(int $num): ?string
    {
        if ($num < 1) {
            throw new InvalidArgumentException(
                'Segment number must be greater than 0'
            );
        }

        $num -= 1;

        return $this->segments[$num] ?? null;
    }

    public function getLastSegment(): ?string
    {
        $num = max(count($this->segments) - 1, 0);

        return $this->segments[$num] ?? null;
    }

    public function getTotalSegments(): int
    {
        return count($this->segments);
    }

    public function getPath(): string
    {
        return implode('/', $this->segments);
    }

    public function getPathFromSegmentSlice(int $length, int $offset = 0): string
    {
        $limitedSegments = array_slice(
            $this->segments,
            $offset,
            $length
        );

        return implode('/', $limitedSegments);
    }

    /**
     * @return array<int, string>
     */
    public function getSegmentsSansPagination(): array
    {
        return $this->segmentsSansPagination;
    }

    public function getLastSegmentSansPagination(): ?string
    {
        $num = max(count($this->segmentsSansPagination) - 1, 0);

        return $this->segmentsSansPagination[$num] ?? null;
    }

    public function getTotalSegmentsSansPagination(): int
    {
        return count($this->segmentsSansPagination);
    }

    public function getPathSansPagination(): string
    {
        return implode('/', $this->segmentsSansPagination);
    }

    public function getPageNum(): int
    {
        return $this->pageNum;
    }

    public function hasPaginationTrigger(): bool
    {
        return $this->hasPaginationTrigger;
    }
}

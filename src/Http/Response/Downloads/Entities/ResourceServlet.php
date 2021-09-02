<?php

declare(strict_types=1);

namespace App\Http\Response\Downloads\Entities;

use DaveRandom\Resume\DefaultOutputWriter;
use DaveRandom\Resume\OutputWriter;
use DaveRandom\Resume\Range;
use DaveRandom\Resume\RangeSet;
use DaveRandom\Resume\RangeUnitProvider;
use DaveRandom\Resume\Resource as ResumeResource;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\ServerRequestCreatorFactory;

use function implode;
use function mb_strtolower;
use function trim;

class ResourceServlet
{
    private ResumeResource $resource;

    /**
     * Generate the default response headers for this resource
     *
     * @return string[]
     */
    private function generateDefaultHeaders(): array
    {
        $ranges = $this->resource instanceof RangeUnitProvider
            ? implode(',', $this->resource->getRangeUnits())
            : 'bytes';

        if ($ranges === '') {
            $ranges = 'none';
        }

        return [
            'content-type' => $this->resource->getMimeType(),
            'accept-ranges' => $ranges,
        ];
    }

    /**
     * Send the headers that are included regardless of whether a range
     * was requested
     */
    private function sendHeaders(OutputWriter $outputWriter): void
    {
        $headers = $this->generateDefaultHeaders();

        /** @psalm-suppress MixedAssignment */
        foreach ($this->resource->getAdditionalHeaders() as $name => $value) {
            $headers[mb_strtolower((string) $name)] = (string) $value;
        }

        foreach ($headers as $name => $value) {
            $outputWriter->sendHeader(
                trim((string) $name),
                trim($value)
            );
        }
    }

    /**
     * Create a Content-Range header corresponding to the specified unit
     * and ranges
     *
     * @param Range[] $ranges
     */
    public function getContentRangeHeader(
        string $unit,
        array $ranges,
        int $size
    ): string {
        return $unit . ' ' . implode(',', $ranges) . '/' . $size;
    }

    public function __construct(ResumeResource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Send data from a file based on the current Range header
     *
     * @param RangeSet|null     $rangeSet     Range header on which the transmission will be based
     * @param OutputWriter|null $outputWriter Output writer via which resource will be sent
     */
    public function sendResource(
        ?RangeSet $rangeSet = null,
        ?OutputWriter $outputWriter = null,
        ?ServerRequestInterface $request = null
    ): void {
        $outputWriter ??= new DefaultOutputWriter();

        $request ??= ServerRequestCreatorFactory::create()
            ->createServerRequestFromGlobals();

        // Send the requested ranges
        $size = $this->resource->getLength();

        if ($rangeSet === null) {
            // No ranges requested, just send the whole file
            $outputWriter->setResponseCode(200);

            $this->sendHeaders($outputWriter);

            $outputWriter->sendHeader(
                'content-length',
                (string) $size,
            );

            if (mb_strtolower($request->getMethod()) === 'head') {
                return;
            }

            $this->resource->sendData($outputWriter);

            return;
        }

        $ranges = $rangeSet->getRangesForSize($size);

        $outputWriter->setResponseCode(206);

        $this->sendHeaders($outputWriter);

        $outputWriter->sendHeader(
            'Content-Range',
            $this->getContentRangeHeader(
                $rangeSet->getUnit(),
                $ranges,
                $size
            ),
        );

        foreach ($ranges as $range) {
            $outputWriter->sendHeader(
                'content-length',
                (string) $range->getLength(),
            );

            if (mb_strtolower($request->getMethod()) === 'head') {
                continue;
            }

            $this->resource->sendData(
                $outputWriter,
                $range
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Context\Content\Entities;

use DateTimeImmutable;
use DateTimeZone;

use function assert;

class ContentItem
{
    private DateTimeImmutable $date;

    public function __construct(
        private string $title,
        private string $slug,
        private string $dateString,
        private string $rawBody,
        private string $body,
    ) {
        $date = DateTimeImmutable::createFromFormat(
            'Y-m-d g:i A',
            $this->dateString,
            new DateTimeZone('US/Central'),
        );

        assert($date instanceof DateTimeImmutable);

        $this->date = $date;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function dateString(): string
    {
        return $this->dateString;
    }

    public function rawBody(): string
    {
        return $this->rawBody;
    }

    public function body(): string
    {
        return $this->body;
    }

    /**
     * @return array<string, string>
     */
    public function __serialize(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'dateString' => $this->dateString,
            'rawBody' => $this->rawBody,
            'body' => $this->rawBody,
        ];
    }

    /**
     * @param array<string, string> $data
     */
    public function __unserialize(array $data): void
    {
        $this->__construct(
            title: $data['title'],
            slug: $data['slug'],
            dateString: $data['dateString'],
            rawBody: $data['rawBody'],
            body: $data['body'],
        );
    }
}

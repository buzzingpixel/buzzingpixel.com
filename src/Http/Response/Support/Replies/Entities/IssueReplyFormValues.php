<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Replies\Entities;

use App\EntityValueObjects\StringValueNonEmpty;
use Throwable;

use function count;

class IssueReplyFormValues
{
    /** @var array<string, string> */
    private array $errorMessages = [];

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValueNonEmpty $comment;

    /**
     * @param mixed[] $post
     */
    public static function fromPostArray(array $post): self
    {
        return new self(
            comment: (string) ($post['comment'] ?? ''),
        );
    }

    public function __construct(string $comment)
    {
        try {
            $this->comment = StringValueNonEmpty::fromString(value: $comment);
        } catch (Throwable $e) {
            $this->errorMessages['comment'] = $e->getMessage();
        }
    }

    public function isValid(): bool
    {
        return count($this->errorMessages) < 1;
    }

    public function isNotValid(): bool
    {
        return ! $this->isValid();
    }

    /**
     * @return array<string, string>
     */
    public function errorMessages(): array
    {
        return $this->errorMessages;
    }

    public function comment(): StringValueNonEmpty
    {
        return $this->comment;
    }

    /**
     * @return mixed[]
     *
     * @psalm-suppress RedundantPropertyInitializationCheck
     */
    public function valuesForHtml(): array
    {
        return [
            'comment' => isset($this->comment) ?
                $this->comment->toString() :
                '',
        ];
    }
}

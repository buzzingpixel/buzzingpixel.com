<?php

declare(strict_types=1);

namespace App\Http\Response\Api\CheckLicense\Entities;

use function json_encode;

class Response
{
    public function __construct(
        private string $message,
        private string $reason,
    ) {
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return [
            'success' => true,
            'message' => $this->message,
            'reason' => $this->reason,
            'items' => [],
        ];
    }

    public function toJson(): string
    {
        return (string) json_encode($this->toArray());
    }
}

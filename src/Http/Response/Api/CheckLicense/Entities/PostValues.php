<?php

declare(strict_types=1);

namespace App\Http\Response\Api\CheckLicense\Entities;

use App\EntityValueObjects\StringValue;
use App\EntityValueObjects\StringValueNonEmpty;
use Throwable;

use function count;

class PostValues
{
    /** @var array<string, string> */
    private array $errorMessages = [];

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValueNonEmpty $app;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValueNonEmpty $domain;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValueNonEmpty $license;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValue $version;

    /**
     * @param mixed[] $post
     */
    public static function fromPostArray(array $post): self
    {
        return new self(
            app: (string) ($post['app'] ?? ''),
            domain: (string) ($post['domain'] ?? ''),
            license: (string) ($post['license'] ?? ''),
            version: (string) ($post['version'] ?? ''),
        );
    }

    public function __construct(
        string $app,
        string $domain,
        string $license,
        string $version,
    ) {
        try {
            $this->app = StringValueNonEmpty::fromString(
                value: $app,
            );
        } catch (Throwable $e) {
            $this->errorMessages['app'] = $e->getMessage();
        }

        try {
            $this->domain = StringValueNonEmpty::fromString(
                value: $domain,
            );
        } catch (Throwable $e) {
            $this->errorMessages['domain'] = $e->getMessage();
        }

        try {
            $this->license = StringValueNonEmpty::fromString(
                value: $license,
            );
        } catch (Throwable $e) {
            $this->errorMessages['license'] = $e->getMessage();
        }

        $this->version = StringValue::fromString(value: $version);
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

    public function app(): StringValueNonEmpty
    {
        return $this->app;
    }

    public function domain(): StringValueNonEmpty
    {
        return $this->domain;
    }

    public function license(): StringValueNonEmpty
    {
        return $this->license;
    }

    public function version(): StringValue
    {
        return $this->version;
    }
}

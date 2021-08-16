<?php

declare(strict_types=1);

namespace App\Http\Response\Contact\Entities;

use App\EntityValueObjects\EmailAddress;
use App\EntityValueObjects\StringValueNonEmpty;
use Throwable;

use function count;

class FormValues
{
    /** @var array<string, string> */
    private array $errorMessages = [];

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValueNonEmpty $name;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private EmailAddress $email;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValueNonEmpty $message;

    public function __construct(
        string $name,
        string $email,
        string $message,
    ) {
        try {
            $this->name = StringValueNonEmpty::fromString(value: $name);
        } catch (Throwable $e) {
            $this->errorMessages['your_name'] = $e->getMessage();
        }

        try {
            $this->email = EmailAddress::fromString(emailAddress: $email);
        } catch (Throwable $e) {
            $this->errorMessages['your_email'] = $e->getMessage();
        }

        try {
            $this->message = StringValueNonEmpty::fromString(value: $message);
        } catch (Throwable $e) {
            $this->errorMessages['message'] = $e->getMessage();
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

    public function name(): StringValueNonEmpty
    {
        return $this->name;
    }

    public function email(): EmailAddress
    {
        return $this->email;
    }

    public function message(): StringValueNonEmpty
    {
        return $this->message;
    }
}

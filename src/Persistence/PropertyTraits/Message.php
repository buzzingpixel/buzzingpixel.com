<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait Message
{
    /**
     * @Mapping\Column(
     *     name="message",
     *     type="text",
     * )
     */
    protected string $message = '';

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}

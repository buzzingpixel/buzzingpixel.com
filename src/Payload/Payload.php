<?php

declare(strict_types=1);

namespace App\Payload;

use LogicException;

use function defined;

class Payload
{
    public const STATUS_SUCCESSFUL  = 'SUCCESSFUL';
    public const STATUS_CREATED     = 'CREATED';
    public const STATUS_DELETED     = 'DELETED';
    public const STATUS_ERROR       = 'ERROR';
    public const STATUS_FOUND       = 'FOUND';
    public const STATUS_NEW         = 'NEW';
    public const STATUS_NOT_CREATED = 'NOT_CREATED';
    public const STATUS_NOT_DELETED = 'NOT_DELETED';
    public const STATUS_NOT_FOUND   = 'NOT_FOUND';
    public const STATUS_NOT_UPDATED = 'NOT_UPDATED';
    public const STATUS_NOT_VALID   = 'NOT_VALID';
    public const STATUS_UPDATED     = 'UPDATED';
    public const STATUS_VALID       = 'VALID';

    private string $status = '';

    /** @var mixed[] */
    private array $result = [];

    private bool $isInitialized = false;

    /**
     * @param mixed[] $result
     */
    public function __construct(string $status, array $result = [])
    {
        if ($this->isInitialized) {
            throw new LogicException(
                'Payload instances can only be initialized once.'
            );
        }

        if (! defined(self::class . '::STATUS_' . $status)) {
            throw new LogicException('$status is invalid');
        }

        $this->status = $status;
        $this->result = $result;

        $this->isInitialized = true;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return mixed[]
     */
    public function getResult(): array
    {
        return $this->result;
    }
}

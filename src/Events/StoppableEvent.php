<?php

declare(strict_types=1);

namespace App\Events;

use Psr\EventDispatcher\StoppableEventInterface;

abstract class StoppableEvent implements StoppableEventInterface
{
    private bool $isPropagationStopped = false;

    public function stopPropagation(bool $stop = true): void
    {
        $this->isPropagationStopped = $stop;
    }

    public function isPropagationStopped(): bool
    {
        return $this->isPropagationStopped;
    }
}

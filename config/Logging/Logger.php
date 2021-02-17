<?php

declare(strict_types=1);

namespace Config\Logging;

use function debug_backtrace;

class Logger extends \Monolog\Logger
{
    /**
     * @param mixed[] $context
     */
    public function addRecord(
        int $level,
        string $message,
        array $context = []
    ): bool {
        $trace = debug_backtrace();

        $callerInfo = $trace[1] ?? null;

        if ($callerInfo !== null) {
            $context['file'] = (string) ($callerInfo['file'] ?? '');
            $context['line'] = (string) ($callerInfo['line'] ?? '');
        }

        return parent::addRecord(
            $level,
            $message,
            $context
        );
    }
}

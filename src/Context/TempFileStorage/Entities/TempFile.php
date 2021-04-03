<?php

declare(strict_types=1);

namespace App\Context\TempFileStorage\Entities;

use App\EntityPropertyTraits\FileName;
use App\EntityPropertyTraits\FilePath;
use LogicException;

use function json_encode;

class TempFile
{
    use FilePath;
    use FileName;

    public function __construct(
        string $filePath,
        string $fileName,
    ) {
        if ($this->isInitialized) {
            throw new LogicException(
                'This object can only be constructed once'
            );
        }

        $this->filePath = $filePath;

        $this->fileName = $fileName;

        $this->isInitialized = true;
    }

    private bool $isInitialized = false;

    public function toJson(): string
    {
        return (string) json_encode([
            'filePath' => $this->filePath(),
            'fileName' => $this->fileName(),
        ]);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}

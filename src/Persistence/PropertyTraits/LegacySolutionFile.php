<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait LegacySolutionFile
{
    /**
     * @Mapping\Column(
     *     name="legacy_solution_file",
     *     type="string",
     * )
     */
    protected string $legacySolutionFile = '';

    public function getLegacySolutionFile(): string
    {
        return $this->legacySolutionFile;
    }

    public function setLegacySolutionFile(string $legacySolutionFile): void
    {
        $this->legacySolutionFile = $legacySolutionFile;
    }
}

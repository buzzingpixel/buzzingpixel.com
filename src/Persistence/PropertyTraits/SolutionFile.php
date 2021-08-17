<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait SolutionFile
{
    /**
     * @Mapping\Column(
     *     name="solution_file",
     *     type="string",
     * )
     */
    protected string $solutionFile = '';

    public function getSolutionFile(): string
    {
        return $this->solutionFile;
    }

    public function setSolutionFile(string $solutionFile): void
    {
        $this->solutionFile = $solutionFile;
    }
}

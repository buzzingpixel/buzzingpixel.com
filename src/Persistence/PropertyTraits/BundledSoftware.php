<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait BundledSoftware
{
    /**
     * @Mapping\Column(
     *     name="bundled_software",
     *     type="json",
     *     options={"default" : "[]"},
     * )
     * @var string[]
     */
    protected array $bundledSoftware = [];

    /**
     * @return string[]
     */
    public function getBundledSoftware(): array
    {
        return $this->bundledSoftware;
    }

    /**
     * @param string[] $bundledSoftware
     */
    public function setBundledSoftware(array $bundledSoftware): void
    {
        $this->bundledSoftware = $bundledSoftware;
    }
}

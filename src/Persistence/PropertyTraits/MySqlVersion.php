<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait MySqlVersion
{
    /**
     * @Mapping\Column(
     *     name="mysql_version",
     *     type="string",
     * )
     */
    protected string $mySqlVersion = '';

    public function getMySqlVersion(): string
    {
        return $this->mySqlVersion;
    }

    public function setMySqlVersion(string $mySqlVersion): void
    {
        $this->mySqlVersion = $mySqlVersion;
    }
}

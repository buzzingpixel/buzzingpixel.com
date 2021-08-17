<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait MySqlVersion
{
    private string $mySqlVersion;

    public function mySqlVersion(): string
    {
        return $this->mySqlVersion;
    }

    /**
     * @return $this
     */
    public function withMySqlVersion(string $mySqlVersion): self
    {
        $clone = clone $this;

        $clone->mySqlVersion = $mySqlVersion;

        return $clone;
    }
}

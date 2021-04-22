<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait AuthorizedDomains
{
    /**
     * @Mapping\Column(
     *     name="authorized_domains",
     *     type="json",
     * )
     * @var string[]
     */
    protected array $authorizedDomains = [];

    /**
     * @return string[]
     */
    public function getAuthorizedDomains(): array
    {
        return $this->authorizedDomains;
    }

    /**
     * @param string[] $authorizedDomains
     */
    public function setAuthorizedDomains(array $authorizedDomains): void
    {
        $this->authorizedDomains = $authorizedDomains;
    }
}

<?php

declare(strict_types=1);

namespace App\EntityPropertyTraits;

trait AuthorizedDomains
{
    /** @var string[] */
    private array $authorizedDomains;

    /**
     * @return string[]
     */
    public function authorizedDomains(): array
    {
        return $this->authorizedDomains;
    }

    /**
     * @param string[] $authorizedDomains
     */
    public function withAuthorizedDomains(array $authorizedDomains): self
    {
        $clone = clone $this;

        $clone->authorizedDomains = $authorizedDomains;

        return $clone;
    }

    public function withAuthorizedDomain(string $authorizedDomain): self
    {
        $clone = clone $this;

        $clone->authorizedDomains[] = $authorizedDomain;

        return $clone;
    }

    public function withRemovedAuthorizedDomain(string $removedDomain): self
    {
        $clone = clone $this;

        $domains = $clone->authorizedDomains;

        foreach ($domains as $key => $domain) {
            if ($domain !== $removedDomain) {
                continue;
            }

            unset($domains[$key]);
        }

        $clone->authorizedDomains = $domains;

        return $clone;
    }
}

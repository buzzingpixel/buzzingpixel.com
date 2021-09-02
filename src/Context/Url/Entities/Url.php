<?php

declare(strict_types=1);

namespace App\Context\Url\Entities;

use League\Uri\Contracts\UriInterface;
use League\Uri\Uri;

use function array_slice;
use function assert;
use function count;
use function explode;
use function implode;
use function mb_stripos;

class Url implements UriInterface
{
    /** @var string[] */
    private array $domainParts = [];

    private string $subDomain = '';

    /** @var string[] */
    private array $subDomainParts = [];

    private string $primaryDomain = '';

    private string $primaryDomainWithoutTld = '';

    private string $tld = '';

    public static function createFromString(string $url = ''): self
    {
        $http = mb_stripos(
            $url,
            'http://',
        );

        $https = mb_stripos(
            $url,
            'https://',
        );

        if ($http !== 0 && $https !== 0) {
            $url = 'http://' . $url;
        }

        return new self(leagueUri: Uri::createFromString($url));
    }

    public function __construct(private Uri $leagueUri)
    {
        $host = (string) $leagueUri->getHost();

        $this->domainParts = explode(
            '.',
            (string) $leagueUri->getHost()
        );

        $this->subDomainParts = array_slice(
            $this->domainParts,
            0,
            count($this->domainParts) - 2
        );

        $this->subDomain = implode('.', $this->subDomainParts);

        if (count($this->domainParts) === 1) {
            $this->primaryDomain           = $host;
            $this->primaryDomainWithoutTld = $host;
        } else {
            $primaryDomainParts = array_slice(
                $this->domainParts,
                count($this->subDomainParts),
            );

            $this->primaryDomain = implode(
                '.',
                $primaryDomainParts
            );

            $this->primaryDomainWithoutTld = $primaryDomainParts[0];

            if (count($primaryDomainParts) > 1) {
                $this->tld = $primaryDomainParts[1];
            }
        }
    }

    /**
     * @return string[]
     */
    public function getDomainParts(): array
    {
        return $this->domainParts;
    }

    public function getSubDomain(): string
    {
        return $this->subDomain;
    }

    /**
     * @return string[]
     */
    public function getSubDomainParts(): array
    {
        return $this->subDomainParts;
    }

    public function getPrimaryDomain(): string
    {
        return $this->primaryDomain;
    }

    public function getPrimaryDomainWithoutTld(): string
    {
        return $this->primaryDomainWithoutTld;
    }

    public function getTld(): string
    {
        return $this->tld;
    }

    public function toString(): string
    {
        return $this->leagueUri->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    public function getScheme(): ?string
    {
        return $this->leagueUri->getScheme();
    }

    public function getAuthority(): ?string
    {
        return $this->leagueUri->getAuthority();
    }

    public function getUserInfo(): ?string
    {
        return $this->leagueUri->getUserInfo();
    }

    public function getHost(): ?string
    {
        return $this->leagueUri->getHost();
    }

    public function getPort(): ?int
    {
        return $this->leagueUri->getPort();
    }

    public function getPath(): string
    {
        return $this->leagueUri->getPath();
    }

    public function getQuery(): ?string
    {
        return $this->leagueUri->getQuery();
    }

    public function getFragment(): ?string
    {
        return $this->leagueUri->getFragment();
    }

    public function withScheme(?string $scheme): self
    {
        $leagueUri = $this->leagueUri->withScheme($scheme);

        assert($leagueUri instanceof Uri);

        return new self(leagueUri: $leagueUri);
    }

    public function withUserInfo(?string $user, ?string $password = null): self
    {
        $leagueUri = $this->leagueUri->withUserInfo(
            $user,
            $password,
        );

        assert($leagueUri instanceof Uri);

        return new self(leagueUri: $leagueUri);
    }

    public function withHost(?string $host): self
    {
        $leagueUri = $this->leagueUri->withHost($host);

        assert($leagueUri instanceof Uri);

        return new self(leagueUri: $leagueUri);
    }

    public function withPort(?int $port): self
    {
        $leagueUri = $this->leagueUri->withPort($port);

        assert($leagueUri instanceof Uri);

        return new self(leagueUri: $leagueUri);
    }

    public function withPath(string $path): self
    {
        $leagueUri = $this->leagueUri->withPath($path);

        assert($leagueUri instanceof Uri);

        return new self(leagueUri: $leagueUri);
    }

    public function withQuery(?string $query): self
    {
        $leagueUri = $this->leagueUri->withQuery($query);

        assert($leagueUri instanceof Uri);

        return new self(leagueUri: $leagueUri);
    }

    public function withFragment(?string $fragment): self
    {
        $leagueUri = $this->leagueUri->withFragment($fragment);

        assert($leagueUri instanceof Uri);

        return new self(leagueUri: $leagueUri);
    }
}

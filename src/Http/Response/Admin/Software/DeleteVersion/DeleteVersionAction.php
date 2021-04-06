<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\DeleteVersion;

use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class DeleteVersionAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private DeleteVersionResponder $responder,
    ) {
    }

    /**
     * @throws HttpNotFoundException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $softwareSlug = (string) $request->getAttribute('softwareSlug');

        $software = $this->softwareApi->fetchOneSoftware(
            (new SoftwareQueryBuilder())
                ->withSlug($softwareSlug),
        );

        if ($software === null) {
            throw new HttpNotFoundException($request);
        }

        $versionSlug = (string) $request->getAttribute('versionSlug');

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $version = $software->versions()->filter(
            static fn (SoftwareVersion $v) => $v->version() === $versionSlug
        )->firstOrNull();

        if ($version === null) {
            throw new HttpNotFoundException($request);
        }

        return $this->responder->respond(
            $this->softwareApi->deleteSoftwareVersion(
                $version
            ),
            $software->adminBaseLink(),
        );
    }
}

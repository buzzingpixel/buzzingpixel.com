<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\Delete;

use App\Context\Software\SoftwareApi;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class DeleteSoftwareAction
{
    public function __construct(
        private SoftwareApi $softwareApi,
        private DeleteSoftwareResponder $responder,
    ) {
    }

    /**
     * @throws HttpNotFoundException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $slug = (string) $request->getAttribute('slug');

        $software = $this->softwareApi->fetchOneSoftware(
            (new SoftwareQueryBuilder())
                ->withSlug($slug),
        );

        if ($software === null) {
            throw new HttpNotFoundException($request);
        }

        return $this->responder->respond(
            $this->softwareApi->deleteSoftware($software),
            '/admin/software',
        );
    }
}

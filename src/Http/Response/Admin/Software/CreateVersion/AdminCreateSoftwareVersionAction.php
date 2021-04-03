<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\CreateVersion;

use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Flash\Messages;
use Twig\Environment as TwigEnvironment;

class AdminCreateSoftwareVersionAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
        private Messages $flash,
        private SoftwareApi $softwareApi,
    ) {
    }

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

        /** @var mixed[] $postData */
        $postData = $this->flash->getMessage('FormMessage')[0]['post_data'] ?? [];

        $response = $this->responseFactory->createResponse();

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['software']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/Software/AdminSoftwareVersionCreateEdit.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Add Software Version | ' . $software->name() . ' | Admin',
                ),
                'accountMenu' => $adminMenu,
                'formAction' => '/admin/software/' . $software->slug() . '/add-version',
                'headline' => 'Add Software Version to ' . $software->name(),
                'breadcrumbSingle' => [
                    'content' => $software->name(),
                    'uri' => '/admin/software/' . $software->slug(),
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Admin',
                        'uri' => '/admin',
                    ],
                    [
                        'content' => 'Software',
                        'uri' => '/admin/software',
                    ],
                    [
                        'content' => $software->name(),
                        'uri' => '/admin/software/' . $software->slug(),
                    ],
                    ['content' => 'Add Version'],
                ],
                'softwareVersion' => new SoftwareVersion(
                    majorVersion: '',
                    version: '',
                    upgradePrice: 0,
                    releasedOn: null,
                ),
            ],
        ));

        return $response;
    }
}

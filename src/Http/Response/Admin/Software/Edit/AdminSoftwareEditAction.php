<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\Edit;

use App\Context\Software\SoftwareApi;
use App\Http\Entities\Meta;
use App\Http\Response\Admin\Software\SoftwareConfig;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Flash\Messages;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminSoftwareEditAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
        private Messages $flash,
        private SoftwareApi $softwareApi,
    ) {
    }

    /**
     * @throws HttpNotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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

        /** @var mixed[] $postData */
        $postData = $this->flash->getMessage('FormMessage')[0]['post_data'] ?? [];

        $response = $this->responseFactory->createResponse();

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['software']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminForm.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Edit ' . $software->name() . ' | Admin',
                ),
                'accountMenu' => $adminMenu,
                'headline' => 'Edit ' . $software->name(),
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
                    ['content' => 'Edit'],
                ],
                'formConfig' => [
                    'submitContent' => 'Submit Edits',
                    'cancelAction' => '/admin/software/' . $software->slug(),
                    'formAction' => '/admin/software/' . $software->slug() . '/edit',
                    'inputs' => SoftwareConfig::getCreateEditFormConfigInputs(
                        $postData,
                        $software,
                    ),
                ],
            ],
        ));

        return $response;
    }
}

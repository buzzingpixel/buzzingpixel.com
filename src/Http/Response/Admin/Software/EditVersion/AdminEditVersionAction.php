<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\EditVersion;

use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Context\Users\Entities\LoggedInUser;
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

class AdminEditVersionAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
        private Messages $flash,
        private SoftwareApi $softwareApi,
        private LoggedInUser $loggedInUser,
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
                    metaTitle: 'Edit ' . $version->name() . ' | Software | Admin',
                ),
                'headline' => 'Edit ' . $version->name(),
                'accountMenu' => $adminMenu,
                'breadcrumbSingle' => [
                    'content' => $version->name(),
                    'uri' => $version->adminBaseLink(),
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
                        'uri' => $software->adminBaseLink(),
                    ],
                    [
                        'content' => $version->name(),
                        'uri' => $version->adminBaseLink(),
                    ],
                    ['content' => 'Edit Version'],
                ],
                'formConfig' => [
                    'submitContent' => 'Submit Edits',
                    'cancelAction' => $version->adminBaseLink(),
                    'formAction' => $version->adminEditLink(),
                    'inputs' => SoftwareConfig::getCreateEditVersionFormConfigInputs(
                        $this->loggedInUser->user(),
                        $postData,
                        $version,
                    ),
                ],
            ],
        ));

        return $response;
    }
}

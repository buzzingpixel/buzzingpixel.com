<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\CreateVersion;

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

class AdminCreateSoftwareVersionAction
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
            queryBuilder: (new SoftwareQueryBuilder())
                ->withSlug($softwareSlug),
        );

        if ($software === null) {
            throw new HttpNotFoundException($request);
        }

        /** @var mixed[] $postData */
        $postData = $this->flash->getMessage('FormMessage')[0]['post_data'] ?? [];

        $response = $this->responseFactory->createResponse();

        $adminMenu = $this->config->adminMenu();

        $adminMenu['software']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminForm.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Add Software Version | ' . $software->name() . ' | Admin',
                ),
                'accountMenu' => $adminMenu,
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
                        'uri' => $software->adminBaseLink(),
                    ],
                    ['content' => 'Add Version'],
                ],
                'formConfig' => [
                    'submitContent' => 'Add',
                    'cancelAction' => $software->adminBaseLink(),
                    'formAction' => $software->adminAddVersionLink(),
                    'inputs' => SoftwareConfig::getCreateEditVersionFormConfigInputs(
                        $this->loggedInUser->user(),
                        $postData,
                    ),
                ],
            ],
        ));

        return $response;
    }
}

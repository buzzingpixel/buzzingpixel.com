<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\Create;

use App\Context\Software\SoftwareApi;
use App\Http\Entities\Meta;
use App\Http\Response\Admin\Software\SoftwareConfig;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminSoftwareCreateAction
{
    public function __construct(
        private General $config,
        private Messages $flash,
        private TwigEnvironment $twig,
        private SoftwareApi $softwareApi,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(): ResponseInterface
    {
        /** @var mixed[] $postData */
        $postData = $this->flash->getMessage('FormMessage')[0]['post_data'] ?? [];

        $response = $this->responseFactory->createResponse();

        $adminMenu = $this->config->adminMenu();

        $adminMenu['software']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminForm.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Create Software | Admin',
                ),
                'accountMenu' => $adminMenu,
                'headline' => 'Create Software',
                'breadcrumbSingle' => [
                    'content' => 'Software',
                    'uri' => '/admin/software',
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
                    ['content' => 'Create'],
                ],
                'formConfig' => [
                    'submitContent' => 'Create Software',
                    'cancelAction' => '/admin/software',
                    'formAction' => '/admin/software/create',
                    'inputs' => SoftwareConfig::getCreateEditFormConfigInputs(
                        postData: $postData,
                        softwareApi: $this->softwareApi,
                    ),
                ],
            ],
        ));

        return $response;
    }
}

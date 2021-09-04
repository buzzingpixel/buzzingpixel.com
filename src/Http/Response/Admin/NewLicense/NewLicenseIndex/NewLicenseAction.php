<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\NewLicense\NewLicenseIndex;

use App\Context\Software\SoftwareApi;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class NewLicenseAction
{
    public function __construct(
        private General $config,
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
        $adminMenu = $this->config->adminMenu();

        $adminMenu['addNewLicense']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminForm.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Create License | Admin',
                ),
                'accountMenu' => $adminMenu,
                'headline' => 'Create License',
                'breadcrumbSingle' => [
                    'content' => 'Admin',
                    'uri' => '/admin',
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Admin',
                        'uri' => '/admin',
                    ],
                    ['content' => 'Create License'],
                ],
                'formConfig' => [
                    'hideTopButtons' => true,
                    'submitContent' => 'Create License',
                    'formAction' => '/admin/new-license',
                    'inputs' => [
                        [
                            'label' => 'User Email Address',
                            'subHeading' => 'If user does not exist, user will be created',
                            'name' => 'user_email_address',
                        ],
                        [
                            'template' => 'Select',
                            'label' => 'Software',
                            'name' => 'software_slug',
                            'options' => $this->softwareApi
                                ->fetchSoftwareAsOptionsArray(),
                        ],
                    ],
                ],
            ],
        ));

        return $response;
    }
}

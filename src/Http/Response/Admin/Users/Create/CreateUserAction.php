<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\Create;

use App\Http\Entities\Meta;
use App\Http\Response\Admin\Users\UserConfig;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function json_encode;

class CreateUserAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
        private Messages $flash,
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

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['users']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminForm.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Create User | Admin',
                ),
                'accountMenu' => $adminMenu,
                'headline' => 'Create User',
                'breadcrumbSingle' => [
                    'content' => 'Users',
                    'uri' => '/admin/users',
                ],
                'breadcrumbTrail' => [
                    [
                        'content' => 'Admin',
                        'uri' => '/admin',
                    ],
                    [
                        'content' => 'Users',
                        'uri' => '/admin/users',
                    ],
                    ['content' => 'Create'],
                ],
                'formConfig' => [
                    'submitContent' => 'Create User',
                    'cancelAction' => '/admin/users',
                    'formAction' => '/admin/users/create',
                    'formAttrs' => [
                        'x-data' => json_encode([
                            'data' => ['countryRegion' => ''],
                        ]),
                    ],
                    'inputs' => UserConfig::getCreateEditUserFormConfigInputs(
                        $postData,
                    ),
                ],
            ],
        ));

        return $response;
    }
}

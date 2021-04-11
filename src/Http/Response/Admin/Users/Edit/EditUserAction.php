<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users\Edit;

use App\Context\Users\UserApi;
use App\Http\Entities\Meta;
use App\Http\Response\Admin\Users\UserConfig;
use App\Persistence\QueryBuilders\Users\UserQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Flash\Messages;
use Throwable;
use Twig\Environment as TwigEnvironment;

use function json_encode;

class EditUserAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
        private Messages $flash,
        private UserApi $userApi,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $emailAddress = (string) $request->getAttribute('emailAddress');

        $user = $this->userApi->fetchOneUser(
            (new UserQueryBuilder())
                ->withEmailAddress($emailAddress),
        );

        if ($user === null) {
            throw new HttpNotFoundException($request);
        }

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
                    metaTitle: 'Edit User ' . $user->emailAddress() . ' | Admin',
                ),
                'accountMenu' => $adminMenu,
                'headline' => 'Edit User' . $user->emailAddress(),
                'breadcrumbSingle' => [
                    'content' => 'View',
                    'uri' => '/admin/users/' . $user->emailAddress(),
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
                    [
                        'content' => 'View',
                        'uri' => '/admin/users/' . $user->emailAddress(),
                    ],
                    ['content' => 'Edit'],
                ],
                'formConfig' => [
                    'submitContent' => 'Submit Edits',
                    'cancelAction' => $user->adminBaseLink(),
                    'formAction' => $user->adminEditLink(),
                    'formAttrs' => [
                        'x-data' => json_encode([
                            'data' => ['countryRegion' => $user->billingProfile()->billingCountryRegion()],
                        ]),
                    ],
                    'inputs' => UserConfig::getCreateEditUserFormConfigInputs(
                        $postData,
                        $user,
                    ),
                ],
            ],
        ));

        return $response;
    }
}

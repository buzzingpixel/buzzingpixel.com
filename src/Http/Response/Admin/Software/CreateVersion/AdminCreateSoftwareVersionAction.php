<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\CreateVersion;

use App\Context\Software\SoftwareApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use App\Utilities\DateTimeUtility;
use Config\General;
use DateTimeImmutable;
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
                        'uri' => '/admin/software/' . $software->slug(),
                    ],
                    ['content' => 'Add Version'],
                ],
                'formConfig' => [
                    'submitContent' => 'Add',
                    'cancelAction' => '/admin/software/' . $software->slug(),
                    'formAction' => '/admin/software/' . $software->slug() . '/add-version',
                    'inputs' => [
                        [
                            'label' => 'Major Version',
                            'name' => 'major_version',
                            'value' => (string) ($postData['major_version'] ?? ''),
                        ],
                        [
                            'label' => 'Version',
                            'name' => 'version',
                            'value' => (string) ($postData['version'] ?? ''),
                        ],
                        [
                            'template' => 'FileUpload',
                            'label' => 'Download File',
                            'name' => 'download_file',
                            'value' => $postData['download_file'] ?? '',
                        ],
                        [
                            'template' => 'Price',
                            'label' => 'Upgrade Price',
                            'name' => 'upgrade_price',
                            'value' => (string) ($postData['upgrade_price'] ?? ''),
                        ],
                        [
                            'type' => 'datetime-local',
                            'label' => 'Released On',
                            'name' => 'released_on',
                            'value' => (string) (
                                $postData['released_on'] ?? (new DateTimeImmutable())
                                    ->setTimezone(
                                        $this->loggedInUser->user()->timezone()
                                    )
                                    ->format(DateTimeUtility::FLATPICKR_DATETIME_LOCAL_FORMAT)
                            ),
                        ],
                    ],
                ],
            ],
        ));

        return $response;
    }
}

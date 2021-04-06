<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\ViewVersion;

use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use App\Utilities\DateTimeUtility;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SoftwareViewVersionAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
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

        $response = $this->responseFactory->createResponse();

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['software']['isActive'] = true;

        $releasedOnFormatted = $version->releasedOn()
            ->setTimezone($this->loggedInUser->user()->timezone())
            ->format(DateTimeUtility::FLATPICKR_DATETIME_LOCAL_FORMAT);

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminKeyValuePage.twig',
            [
                'meta' => new Meta(
                    metaTitle: $version->name() . ' | Software | Admin',
                ),
                'accountMenu' => $adminMenu,
                'breadcrumbSingle' => [
                    'content' => $software->name(),
                    'uri' => $software->adminBaseLink(),
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
                    ['content' => 'View Version'],
                ],
                'keyValueCard' => [
                    'actionButtons' => [
                        [
                            'colorType' => 'danger',
                            'href' => $version->adminDeleteLink(),
                            'content' => 'Delete',
                        ],
                        [
                            'href' => $version->adminEditLink(),
                            'content' => 'Edit',
                        ],
                    ],
                    'headline' => $version->name(),
                    'subHeadline' => $releasedOnFormatted,
                    'items' => [
                        [
                            'key' => 'Major Version',
                            'value' => $version->majorVersion(),
                        ],
                        [
                            'key' => 'Version',
                            'value' => $version->version(),
                        ],
                        [
                            'key' => 'Download File',
                            // TODO: make this downloadable
                            'value' => $version->downloadFileName(),
                        ],
                        [
                            'key' => 'Upgrade Price',
                            'value' => $version->upgradePriceFormatted(),
                        ],
                        [
                            'key' => 'Released On',
                            'value' => $releasedOnFormatted,
                        ],
                    ],
                ],
            ],
        ));

        return $response;
    }
}

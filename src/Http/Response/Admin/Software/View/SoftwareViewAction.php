<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\View;

use App\Context\Software\Entities\SoftwareCollection;
use App\Context\Software\Entities\SoftwareVersion;
use App\Context\Software\SoftwareApi;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function array_map;
use function count;

class SoftwareViewAction
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private SoftwareApi $softwareApi,
        private ResponseFactoryInterface $responseFactory,
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

        $response = $this->responseFactory->createResponse();

        $adminMenu = $this->config->adminMenu();

        $adminMenu['software']['isActive'] = true;

        $bundledSoftware = new SoftwareCollection();

        if (count($software->bundledSoftware()) > 0) {
            $bundledSoftware = $this->softwareApi->fetchSoftware(
                queryBuilder: (new SoftwareQueryBuilder())
                    ->withSlugsIn(value: $software->bundledSoftware()),
            );
        }

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminKeyValuePage.twig',
            [
                'meta' => new Meta(
                    metaTitle: $software->name() . ' | Software | Admin',
                ),
                'accountMenu' => $adminMenu,
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
                    ['content' => 'View'],
                ],
                'keyValueCard' => [
                    'actionButtons' => [
                        [
                            'colorType' => 'danger',
                            'href' => $software->adminDeleteLink(),
                            'content' => 'Delete',
                        ],
                        [
                            'href' => $software->adminEditLink(),
                            'content' => 'Edit',
                        ],
                    ],
                    'headline' => $software->name(),
                    'subHeadline' => $software->slug(),
                    'items' => [
                        [
                            'key' => 'Slug',
                            'value' => $software->slug(),
                        ],
                        [
                            'key' => 'Name',
                            'value' => $software->name(),
                        ],
                        [
                            'key' => 'Is For Sale?',
                            'value' => $software->isForSale() ? 'Yes' : 'No',
                        ],
                        [
                            'key' => 'Price',
                            'value' => $software->priceFormatted(),
                        ],
                        [
                            'key' => 'Renewal Price',
                            'value' => $software->renewalPriceFormatted(),
                        ],
                        [
                            'key' => 'Is Subscription?',
                            'value' => $software->isSubscription() ? 'Yes' : 'No',
                        ],
                        [
                            'template' => 'Http/_Infrastructure/Display/SimpleTableList.twig',
                            'key' => 'Versions',
                            'value' => [
                                'actionLinks' => [
                                    [
                                        'href' => $software->adminAddVersionLink(),
                                        'content' => 'Add Version',
                                    ],
                                ],
                                'items' => $software->versions()->mapToArray(
                                    static function (SoftwareVersion $version): array {
                                        return [
                                            'content' => $version->version(),
                                            'links' => [
                                                [
                                                    'href' => $version->adminBaseLink(),
                                                    'content' => 'View',
                                                ],
                                            ],
                                        ];
                                    },
                                ),
                            ],
                        ],
                        [
                            'template' => 'Http/_Infrastructure/Display/SimpleTableList.twig',
                            'key' => 'Bundled Software',
                            'value' => [
                                'items' => array_map(
                                    static fn (
                                        string $softwareSlug,
                                    ) => [
                                        'content' => $bundledSoftware->where(
                                            'slug',
                                            $softwareSlug
                                        )->first()->name(),
                                    ],
                                    $software->bundledSoftware()
                                ),
                            ],
                        ],
                    ],
                ],
            ],
        ));

        return $response;
    }
}

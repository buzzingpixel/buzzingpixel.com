<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software;

use App\Context\Software\Entities\Software;
use App\Context\Software\SoftwareApi;
use App\Http\Entities\Meta;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminSoftwareAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
        private SoftwareApi $softwareApi,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['software']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/Software/AdminSoftware.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Software | Admin',
                ),
                'accountMenu' => $adminMenu,
                'stackedListTwoColumnConfig' => [
                    'actionButtons' => [
                        [
                            'href' => '/admin/software/create',
                            'content' => 'New',
                        ],
                    ],
                    'headline' => 'Software',
                    'items' => $this->softwareApi->fetchSoftware(
                        (new SoftwareQueryBuilder())
                            ->withOrderBy('name')
                    )->mapToArray(
                        static function (Software $software): array {
                            return [
                                'href' => $software->adminBaseLink(),
                                'column1Headline' => $software->name(),
                                'column1SubHeadline' => $software->slug(),
                                'column2Headline' => 'Price: ' . $software->priceFormatted(),
                                'column2SubHeadline' => 'Renewal Price: ' . $software->renewalPriceFormatted(),
                            ];
                        }
                    ),
                ],
            ],
        ));

        return $response;
    }
}

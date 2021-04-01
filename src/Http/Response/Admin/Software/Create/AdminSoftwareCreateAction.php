<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Software\Create;

use App\Context\Software\Entities\Software;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Flash\Messages;
use Twig\Environment as TwigEnvironment;

class AdminSoftwareCreateAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private TwigEnvironment $twig,
        private General $config,
        protected Messages $flash,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $postData = $this->flash->getMessage('FormMessage')[0]['post_data'] ?? [];

        $response = $this->responseFactory->createResponse();

        $adminMenu = $this->config->adminMenu();

        /** @psalm-suppress MixedArrayAssignment */
        $adminMenu['software']['isActive'] = true;

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/Software/AdminSoftwareCreateEdit.twig',
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
                'software' => new Software(
                    slug: (string) ($postData['slug'] ?? ''),
                    name: (string) ($postData['name'] ?? ''),
                    isForSale: (bool) ($postData['is_for_sale'] ?? '0'),
                    price: (int) ((float) ($postData['price'] ?? '0') * 100),
                    renewalPrice: (int) ((float) ($postData['renewal_price'] ?? '0') * 100),
                    isSubscription: (bool) ($postData['is_subscription'] ?? '0'),
                ),
            ],
        ));

        return $response;
    }
}

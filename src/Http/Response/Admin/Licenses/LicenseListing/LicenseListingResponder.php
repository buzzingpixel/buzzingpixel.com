<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Licenses\LicenseListing;

use App\Context\Licenses\Entities\License;
use App\Context\Users\Entities\LoggedInUser;
use App\Http\Entities\Meta;
use Config\General;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class LicenseListingResponder implements LicenseListingResponderContract
{
    public function __construct(
        private General $config,
        private TwigEnvironment $twig,
        private LoggedInUser $loggedInUser,
        private LicenseResult $licenseResult,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function respond(): ResponseInterface
    {
        $adminMenu = $this->config->adminMenu();

        $adminMenu['licenses']['isActive'] = true;

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->render(
            '@app/Http/Response/Admin/AdminStackedListTwoColumn.twig',
            [
                'meta' => new Meta(
                    metaTitle: 'Licenses | Admin',
                ),
                'accountMenu' => $adminMenu,
                'stackedListTwoColumnConfig' => [
                    'pagination' => $this->licenseResult->pagination(),
                    'headline' => 'Licenses',
                    'searchAction' => '/admin/licenses',
                    'noResultsContent' => 'No results.',
                    'searchPlaceholder' => 'Search licenses',
                    'searchValue' => $this->licenseResult->searchTerm(),
                    'items' => $this->licenseResult->licenses()->mapToArray(
                        function (License $license): array {
                            return $license->getStackedListTwoColumn(
                                loggedInUser: $this->loggedInUser,
                            );
                        },
                    ),
                ],
            ]
        ));

        return $response;
    }
}

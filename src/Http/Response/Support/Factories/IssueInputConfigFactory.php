<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Factories;

use App\Context\Issues\Entities\Issue;
use App\Context\Issues\Entities\IssueMessage;
use App\Context\Software\SoftwareApi;

use function array_merge;
use function assert;
use function is_array;

class IssueInputConfigFactory
{
    public function __construct(
        private SoftwareApi $softwareApi,
    ) {
    }

    /**
     * @param mixed[] $message
     *
     * @return mixed[]
     */
    public function getInputConfig(
        string $type = 'public',
        ?array $message = null,
        ?Issue $issue = null,
    ): array {
        $inputValues = $message['inputValues'] ?? [];

        assert(is_array($inputValues));

        $isPublic = $type === 'public';

        if ($issue !== null) {
            $isPublic = $issue->isPublic();
        }

        if ($issue === null) {
            $issue = new Issue(
                issueMessages: [new IssueMessage()],
            );
        }

        if (isset($inputValues['is_public'])) {
            $isPublic = ((string) $inputValues['is_public']) === '1';
        }

        $errorMessages = $message['errorMessages'] ?? [];

        assert(is_array($errorMessages));

        $issueSoftwareValue = '';

        $issueSoftware = $issue->software();

        if ($issueSoftware !== null) {
            $issueSoftwareValue = $issueSoftware->id();
        }

        return [
            [
                'template' => 'Toggle',
                'label' => 'Public?',
                'name' => 'is_public',
                'value' => $isPublic,
                'errorMessage' => (string) ($errorMessages['is_public'] ?? ''),
            ],
            [
                'template' => 'Select',
                'label' => 'Software',
                'labelSmall' => '(required)',
                'name' => 'software',
                'options' => $this->softwareApi->fetchSoftwareAsIdOptionsArray(),
                'value' => (string) ($inputValues['software'] ?? $issueSoftwareValue),
                'errorMessage' => (string) ($errorMessages['software'] ?? ''),
            ],
            [
                'label' => 'Software Version',
                'labelSmall' => '(required)',
                'name' => 'software_version',
                'attrs' => ['placeholder' => 'e.g. 2.0.1'],
                'value' => (string) ($inputValues['software_version'] ?? $issue->softwareVersion()),
                'errorMessage' => (string) ($errorMessages['software_version'] ?? ''),
            ],
            [
                'label' => 'Short Description',
                'labelSmall' => '(required)',
                'name' => 'short_description',
                'attrs' => ['placeholder' => 'e.g. Ansel causes the server to explode when submitting an entry'],
                'value' => (string) ($inputValues['short_description'] ?? $issue->shortDescription()),
                'errorMessage' => (string) ($errorMessages['short_description'] ?? ''),
            ],
            [
                'label' => 'CMS Version',
                'name' => 'cms_version',
                'attrs' => ['placeholder' => 'e.g. EE 3.5.3'],
                'value' => (string) ($inputValues['cms_version'] ?? $issue->cmsVersion()),
                'errorMessage' => (string) ($errorMessages['cms_version'] ?? ''),
            ],
            [
                'label' => 'PHP Version',
                'name' => 'php_version',
                'attrs' => ['placeholder' => 'e.g. 8.0.1'],
                'value' => (string) ($inputValues['php_version'] ?? $issue->phpVersion()),
                'errorMessage' => (string) ($errorMessages['php_version'] ?? ''),
            ],
            [
                'label' => 'MySQL Version',
                'name' => 'mysql_version',
                'attrs' => ['placeholder' => 'e.g. MySQL 5.7.12 or MariaDB 10.1.21'],
                'value' => (string) ($inputValues['mysql_version'] ?? $issue->mySqlVersion()),
                'errorMessage' => (string) ($errorMessages['mysql_version'] ?? ''),
            ],
            [
                'template' => 'TextArea',
                'limitedWidth' => false,
                'label' => 'Additional Environment Details',
                'subHeading' => 'use Markdown for formatting',
                'name' => 'additional_env_details',
                'attrs' => ['rows' => 5],
                'value' => (string) ($inputValues['additional_env_details'] ?? $issue->additionalEnvDetails()),
                'errorMessage' => (string) ($errorMessages['additional_env_details'] ?? ''),
            ],
            [
                'template' => 'TextArea',
                'limitedWidth' => false,
                'label' => 'Message',
                'labelSmall' => '(required)',
                'subHeading' => 'use Markdown for formatting',
                'name' => 'message',
                'attrs' => ['rows' => 16],
                'value' => (string) ($inputValues['message'] ?? $issue->issueMessages()->first()->message()),
                'errorMessage' => (string) ($errorMessages['message'] ?? ''),
            ],
            [
                'template' => 'TextArea',
                'limitedWidth' => false,
                'label' => 'Private Info',
                'subHeading' => 'this info will only ever be shown to you and admins<br>use Markdown for formatting',
                'name' => 'private_info',
                'attrs' => ['rows' => 5],
                'value' => (string) ($inputValues['private_info'] ?? $issue->privateInfo()),
                'errorMessage' => (string) ($errorMessages['private_info'] ?? ''),
            ],
        ];
    }

    /**
     * @param mixed[] $message
     *
     * @return mixed[]
     */
    public function getAdminInputConfig(
        string $type = 'public',
        ?array $message = null,
        ?Issue $issue = null,
    ): array {
        $inputValues = $message['inputValues'] ?? [];

        assert(is_array($inputValues));

        $errorMessages = $message['errorMessages'] ?? [];

        assert(is_array($errorMessages));

        $userConfig = $this->getInputConfig(
            type: $type,
            message: $message,
            issue: $issue,
        );

        if ($issue === null) {
            $issue = new Issue(
                issueMessages: [new IssueMessage()],
            );
        }

        return array_merge(
            [
                [
                    'template' => 'Select',
                    'label' => 'Status',
                    'name' => 'status',
                    'options' => Issue::statusSelectOptionsArray(),
                    'value' => (string) ($inputValues['status'] ?? $issue->status()),
                    'errorMessage' => (string) ($errorMessages['status'] ?? ''),
                ],
                [
                    'template' => 'Toggle',
                    'label' => 'Enabled?',
                    'name' => 'is_enabled',
                    'value' => $issue->isEnabled(),
                ],
            ],
            $userConfig,
            [
                [
                    'template' => 'TextArea',
                    'limitedWidth' => false,
                    'label' => 'Solution',
                    'subHeading' => 'use Markdown for formatting',
                    'name' => 'solution',
                    'attrs' => ['rows' => 16],
                    'value' => (string) ($inputValues['solution'] ?? $issue->solution()),
                    'errorMessage' => (string) ($errorMessages['solution'] ?? ''),
                ],
                [
                    'limitedWidth' => false,
                    'label' => 'Solution File',
                    'name' => 'legacy_solution_file',
                    'attrs' => ['placeholder' => 'https://somestorageprovider.com/somefile'],
                    'value' => (string) ($inputValues['legacy_solution_file'] ?? $issue->legacySolutionFile()),
                    'errorMessage' => (string) ($errorMessages['legacy_solution_file'] ?? ''),
                ],
            ],
        );
    }
}

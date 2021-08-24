<?php

declare(strict_types=1);

namespace App\Http\Response\Support\NewIssue\Factories;

use App\Context\Software\SoftwareApi;

class IssueInputConfigFactory
{
    public function __construct(
        private SoftwareApi $softwareApi,
    ) {
    }

    /**
     * @return mixed[]
     */
    public function getInputConfig(
        string $type = 'public',
    ): array {
        return [
            [
                'template' => 'Toggle',
                'label' => 'Public?',
                'name' => 'public',
                'value' => $type === 'public',
            ],
            [
                'template' => 'Select',
                'label' => 'Software',
                'labelSmall' => '(required)',
                'name' => 'public_private',
                'options' => $this->softwareApi->fetchSoftwareAsOptionsArray(),
            ],
            [
                'label' => 'Software Version',
                'labelSmall' => '(required)',
                'name' => 'software_version',
                'attrs' => ['placeholder' => 'e.g. 2.0.1'],
            ],
            [
                'label' => 'Short Description',
                'labelSmall' => '(required)',
                'name' => 'short_description',
                'attrs' => ['placeholder' => 'e.g. Ansel causes the server to explode when submitting an entry'],
            ],
            [
                'label' => 'CMS Version',
                'name' => 'cms_version',
                'attrs' => ['placeholder' => 'e.g. EE 3.5.3'],
            ],
            [
                'label' => 'PHP Version',
                'name' => 'php_version',
                'attrs' => ['placeholder' => 'e.g. 8.0.1'],
            ],
            [
                'label' => 'MySQL Version',
                'name' => 'mysql_version',
                'attrs' => ['placeholder' => 'e.g. MySQL 5.7.12 or MariaDB 10.1.21'],
            ],
            [
                'template' => 'TextArea',
                'limitedWidth' => false,
                'label' => 'Additional Environment Details',
                'subHeading' => 'use Markdown for formatting',
                'name' => 'additional_env_details',
                'attrs' => ['rows' => 5],
            ],
            [
                'template' => 'TextArea',
                'limitedWidth' => false,
                'label' => 'Message',
                'labelSmall' => '(required)',
                'subHeading' => 'use Markdown for formatting',
                'name' => 'message',
                'attrs' => ['rows' => 16],
            ],
            [
                'template' => 'TextArea',
                'limitedWidth' => false,
                'label' => 'Private Info',
                'subHeading' => 'this info will only ever be shown to you and admins<br>use Markdown for formatting',
                'name' => 'private_info',
                'attrs' => ['rows' => 5],
            ],
        ];
    }
}

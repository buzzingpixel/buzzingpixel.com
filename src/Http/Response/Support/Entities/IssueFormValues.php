<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Entities;

use App\Context\Software\Entities\Software;
use App\Context\Software\SoftwareApi;
use App\EntityValueObjects\BooleanValue;
use App\EntityValueObjects\SoftwareNotNull;
use App\EntityValueObjects\StringValue;
use App\EntityValueObjects\StringValueNonEmpty;
use App\Persistence\QueryBuilders\Software\SoftwareQueryBuilder;
use Ramsey\Uuid\Uuid;
use Throwable;

use function count;

class IssueFormValues
{
    /** @var array<string, string> */
    private array $errorMessages = [];

    /** @psalm-suppress PropertyNotSetInConstructor */
    private BooleanValue $isPublic;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private SoftwareNotNull $software;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValueNonEmpty $softwareVersion;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValueNonEmpty $shortDescription;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValue $cmsVersion;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValue $phpVersion;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValue $mySqlVersion;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValue $additionalEnvDetails;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValueNonEmpty $message;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValue $privateInfo;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValue $solution;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValue $status;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private StringValue $legacySolutionFile;

    /**
     * @param mixed[] $post
     */
    public static function fromPostArray(
        array $post,
        SoftwareApi $softwareApi,
    ): self {
        try {
            $softwareUuid = Uuid::fromString(
                (string) ($post['software'] ?? '')
            );
        } catch (Throwable) {
            // Just need a random, invalid one
            $softwareUuid = Uuid::fromString(
                '185b0ffa-aabb-473e-91b9-b02467d58dd3'
            );
        }

        $software = $softwareApi->fetchOneSoftware(
            (new SoftwareQueryBuilder())
                ->withId($softwareUuid->toString())
        );

        return new self(
            isPublic: (bool) ($post['is_public'] ?? '1'),
            software: $software,
            softwareVersion: (string) ($post['software_version'] ?? ''),
            shortDescription: (string) ($post['short_description'] ?? ''),
            cmsVersion: (string) ($post['cms_version'] ?? ''),
            phpVersion: (string) ($post['php_version'] ?? ''),
            mySqlVersion: (string) ($post['mysql_version'] ?? ''),
            additionalEnvDetails: (string) ($post['additional_env_details'] ?? ''),
            message: (string) ($post['message'] ?? ''),
            privateInfo: (string) ($post['private_info'] ?? ''),
            solution: (string) ($post['solution'] ?? ''),
            status: (string) ($post['status'] ?? ''),
            legacySolutionFile: (string) ($post['legacy_solution_file'] ?? ''),
        );
    }

    public function __construct(
        bool $isPublic,
        ?Software $software,
        string $softwareVersion,
        string $shortDescription,
        string $cmsVersion,
        string $phpVersion,
        string $mySqlVersion,
        string $additionalEnvDetails,
        string $message,
        string $privateInfo,
        string $solution,
        string $status,
        string $legacySolutionFile,
    ) {
        $this->isPublic = new BooleanValue($isPublic);

        try {
            /** @phpstan-ignore-next-line */
            $this->software = new SoftwareNotNull(software: $software);
        } catch (Throwable) {
            $this->errorMessages['software'] = 'Valid software must be provided';
        }

        try {
            $this->softwareVersion = StringValueNonEmpty::fromString(
                value: $softwareVersion
            );
        } catch (Throwable $e) {
            $this->errorMessages['software_version'] = $e->getMessage();
        }

        try {
            $this->shortDescription = StringValueNonEmpty::fromString(
                value: $shortDescription
            );
        } catch (Throwable $e) {
            $this->errorMessages['short_description'] = $e->getMessage();
        }

        $this->cmsVersion = StringValue::fromString(value: $cmsVersion);

        $this->phpVersion = StringValue::fromString(value: $phpVersion);

        $this->mySqlVersion = StringValue::fromString(value: $mySqlVersion);

        $this->additionalEnvDetails = StringValue::fromString(
            value: $additionalEnvDetails
        );

        try {
            $this->message = StringValueNonEmpty::fromString(value: $message);
        } catch (Throwable $e) {
            $this->errorMessages['message'] = $e->getMessage();
        }

        $this->privateInfo = StringValue::fromString(value: $privateInfo);

        $this->solution = StringValue::fromString(value: $solution);

        $this->status = StringValue::fromString(value: $status);

        $this->legacySolutionFile = StringValue::fromString(
            value: $legacySolutionFile
        );
    }

    public function isValid(): bool
    {
        return count($this->errorMessages) < 1;
    }

    public function isNotValid(): bool
    {
        return ! $this->isValid();
    }

    /**
     * @return array<string, string>
     */
    public function errorMessages(): array
    {
        return $this->errorMessages;
    }

    public function isPublic(): BooleanValue
    {
        return $this->isPublic;
    }

    public function software(): SoftwareNotNull
    {
        return $this->software;
    }

    public function softwareVersion(): StringValueNonEmpty
    {
        return $this->softwareVersion;
    }

    public function shortDescription(): StringValueNonEmpty
    {
        return $this->shortDescription;
    }

    public function cmsVersion(): StringValue
    {
        return $this->cmsVersion;
    }

    public function phpVersion(): StringValue
    {
        return $this->phpVersion;
    }

    public function mySqlVersion(): StringValue
    {
        return $this->mySqlVersion;
    }

    public function additionalEnvDetails(): StringValue
    {
        return $this->additionalEnvDetails;
    }

    public function message(): StringValueNonEmpty
    {
        return $this->message;
    }

    public function privateInfo(): StringValue
    {
        return $this->privateInfo;
    }

    public function solution(): StringValue
    {
        return $this->solution;
    }

    public function status(): StringValue
    {
        return $this->status;
    }

    public function legacySolutionFile(): StringValue
    {
        return $this->legacySolutionFile;
    }

    /**
     * @return mixed[]
     *
     * @psalm-suppress RedundantPropertyInitializationCheck
     */
    public function valuesForHtml(): array
    {
        return [
            'is_public' => $this->isPublic()->toString(),
            'software' =>  isset($this->software) ?
                $this->software->toString() :
                '',
            'software_version' => isset($this->softwareVersion) ?
                $this->softwareVersion->toString() :
                '',
            'short_description' => isset($this->shortDescription) ?
                $this->shortDescription->toString() :
                '',
            'cms_version' => $this->cmsVersion()->toString(),
            'php_version' => $this->phpVersion()->toString(),
            'mysql_version' => $this->mySqlVersion()->toString(),
            'additional_env_details' => $this->additionalEnvDetails()
                ->toString(),
            'message' => isset($this->message) ?
                $this->message->toString() :
                '',
            'private_info' => $this->privateInfo()->toString(),
            'solution' => $this->solution()->toString(),
            'status' => $this->status()->toString(),
            'legacy_solution_file' => $this->legacySolutionFile()->toString(),
        ];
    }
}

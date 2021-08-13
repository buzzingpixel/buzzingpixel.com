<?php

declare(strict_types=1);

namespace App\Context\Analytics\Entities;

use App\Context\Users\Entities\User as UserEntity;
use App\EntityPropertyTraits\Cookie;
use App\EntityPropertyTraits\Date;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\LoggedInOnPageLoad;
use App\EntityPropertyTraits\Uri;
use App\EntityPropertyTraits\User;
use App\EntityValueObjects\Id as IdValue;
use App\Utilities\DateTimeUtility;
use buzzingpixel\cookieapi\interfaces\CookieInterface;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilitySymbolRequired

class AnalyticsEntity
{
    use Id;
    use Cookie;
    use User;
    use LoggedInOnPageLoad;
    use Uri;
    use Date;

    public function __construct(
        CookieInterface $cookie,
        ?UserEntity $user = null,
        bool $loggedInOnPageLoad = false,
        string $uri = '',
        null | string | DateTimeInterface $date = null,
        null | string | UuidInterface $id = null,
    ) {
        if ($id === null) {
            $this->id = IdValue::create();
        } elseif ($id instanceof UuidInterface) {
            $this->id = IdValue::fromString($id->toString());
        } else {
            $this->id = IdValue::fromString($id);
        }

        $this->cookie = $cookie;

        $this->user = $user;

        $this->loggedInOnPageLoad = $loggedInOnPageLoad;

        $this->uri = $uri;

        $this->date = DateTimeUtility::createDateTimeImmutable(
            dateTime: $date,
        );
    }
}

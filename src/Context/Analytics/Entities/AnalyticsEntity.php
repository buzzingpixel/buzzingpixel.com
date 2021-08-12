<?php

declare(strict_types=1);

namespace App\Context\Analytics\Entities;

use App\EntityPropertyTraits\Cookie;
use App\EntityPropertyTraits\Date;
use App\EntityPropertyTraits\Id;
use App\EntityPropertyTraits\LoggedInOnPageLoad;
use App\EntityPropertyTraits\Uri;
use App\EntityPropertyTraits\User;

class AnalyticsEntity
{
    use Id;
    use Cookie;
    use User;
    use LoggedInOnPageLoad;
    use Uri;
    use Date;
}

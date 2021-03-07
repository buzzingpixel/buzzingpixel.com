<?php

declare(strict_types=1);

namespace App\Context\Users\Events;

use App\Context\Users\Entities\UserEntity;
use App\Events\StoppableEvent;

class SaveUserBeforeSave extends StoppableEvent
{
    public function __construct(public UserEntity $userEntity)
    {
    }
}

<?php

declare(strict_types=1);

namespace App\Context\Users\Events;

use App\Context\Users\Entities\User;
use App\Events\StoppableEvent;

class DeleteUserAfterDelete extends StoppableEvent
{
    public function __construct(public User $userEntity)
    {
    }
}

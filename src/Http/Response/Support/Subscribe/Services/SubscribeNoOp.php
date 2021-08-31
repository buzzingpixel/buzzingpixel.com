<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Services;

use App\Context\Users\Entities\User;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Subscribe\Contracts\SubscribeContract;

class SubscribeNoOp implements SubscribeContract
{
    public function subscribeUser(User $user, GetIssueResults $results): void
    {
    }
}

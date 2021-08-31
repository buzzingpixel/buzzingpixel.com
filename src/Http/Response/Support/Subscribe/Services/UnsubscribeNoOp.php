<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Services;

use App\Context\Users\Entities\User;
use App\Http\Response\Support\Entities\GetIssueResults;
use App\Http\Response\Support\Subscribe\Contracts\UnsubscribeContract;

class UnsubscribeNoOp implements UnsubscribeContract
{
    public function unsubscribeUser(User $user, GetIssueResults $results,): void
    {
    }
}

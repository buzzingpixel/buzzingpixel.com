<?php

declare(strict_types=1);

namespace App\Http\Response\Support\Subscribe\Contracts;

use App\Context\Users\Entities\User;
use App\Http\Response\Support\Entities\GetIssueResults;

interface UnsubscribeContract
{
    public function unsubscribeUser(User $user, GetIssueResults $results): void;
}

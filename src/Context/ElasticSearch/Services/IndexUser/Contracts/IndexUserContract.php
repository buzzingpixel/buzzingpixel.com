<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexUser\Contracts;

use App\Context\Users\Entities\User;

interface IndexUserContract
{
    public function indexUser(User $user): void;
}

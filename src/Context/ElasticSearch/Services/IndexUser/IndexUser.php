<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch\Services\IndexUser;

use App\Context\ElasticSearch\Services\IndexUser\Factories\IndexUserFactory;
use App\Context\Users\Entities\User;

class IndexUser
{
    public function __construct(private IndexUserFactory $indexUserFactory)
    {
    }

    public function indexUser(User $user): void
    {
        $this->indexUserFactory->make(user: $user)->indexUser(user: $user);
    }
}

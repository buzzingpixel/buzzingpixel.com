<?php

declare(strict_types=1);

namespace App\Context\ElasticSearch;

use App\Context\ElasticSearch\Services\IndexIssue\IndexIssue;
use App\Context\ElasticSearch\Services\IndexIssues\IndexIssues;
use App\Context\ElasticSearch\Services\IndexLicense\IndexLicense;
use App\Context\ElasticSearch\Services\IndexLicenses\IndexLicenses;
use App\Context\ElasticSearch\Services\IndexOrder\IndexOrder;
use App\Context\ElasticSearch\Services\IndexOrders\IndexOrders;
use App\Context\ElasticSearch\Services\IndexUser\IndexUser;
use App\Context\ElasticSearch\Services\IndexUsers\IndexUsers;
use App\Context\ElasticSearch\Services\SetUpIndices;
use App\Context\Issues\Entities\Issue;
use App\Context\Licenses\Entities\License;
use App\Context\Orders\Entities\Order;
use App\Context\Users\Entities\User;

class ElasticSearchApi
{
    public function __construct(
        private IndexIssue $indexIssue,
        private SetUpIndices $setUpIndices,
        private IndexIssues $indexIssues,
        private IndexUser $indexUser,
        private IndexUsers $indexUsers,
        private IndexOrder $indexOrder,
        private IndexOrders $indexOrders,
        private IndexLicense $indexLicense,
        private IndexLicenses $indexLicenses,
    ) {
    }

    public function setUpIndices(): void
    {
        $this->setUpIndices->setUp();
    }

    public function indexIssue(Issue $issue): void
    {
        $this->indexIssue->indexIssue(issue: $issue);
    }

    public function indexIssues(): void
    {
        $this->indexIssues->indexIssues();
    }

    public function indexUser(User $user): void
    {
        $this->indexUser->indexUser(user: $user);
    }

    public function indexUsers(): void
    {
        $this->indexUsers->indexUsers();
    }

    public function indexOrder(Order $order): void
    {
        $this->indexOrder->indexOrder(order: $order);
    }

    public function indexOrders(): void
    {
        $this->indexOrders->indexOrders();
    }

    public function indexLicense(License $license): void
    {
        $this->indexLicense->indexLicense(license: $license);
    }

    public function indexLicenses(): void
    {
        $this->indexLicenses->indexLicenses();
    }
}

<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210804141349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE licenses RENAME COLUMN stripe_id TO stripe_subscription_item_id');
        $this->addSql('ALTER TABLE licenses ADD stripe_subscription_id VARCHAR(255) DEFAULT \'\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE licenses RENAME COLUMN stripe_subscription_item_id TO stripe_id');
        $this->addSql('ALTER TABLE licenses DROP stripe_subscription_id');
    }
}

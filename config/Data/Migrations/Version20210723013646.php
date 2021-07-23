<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210723013646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add stripe_id to licenses table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE licenses ADD stripe_id VARCHAR(255) NOT NULL DEFAULT ''");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE licenses DROP stripe_id');
    }
}

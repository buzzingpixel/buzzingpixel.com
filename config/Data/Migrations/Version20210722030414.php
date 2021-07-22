<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210722030414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add quantity column to order_items table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE order_items ADD quantity INT NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE order_items DROP quantity');
    }
}

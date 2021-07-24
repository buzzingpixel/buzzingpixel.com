<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210724151144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_62809db0460f904b');
        $this->addSql('CREATE INDEX IDX_62809DB0460F904B ON order_items (license_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IDX_62809DB0460F904B');
        $this->addSql('CREATE UNIQUE INDEX uniq_62809db0460f904b ON order_items (license_id)');
    }
}

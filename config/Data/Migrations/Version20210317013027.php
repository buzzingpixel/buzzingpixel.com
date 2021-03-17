<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210317013027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the cache_pool table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE cache_pool (id UUID NOT NULL, key TEXT NOT NULL, value TEXT NOT NULL, expires_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN cache_pool.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cache_pool.expires_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE cache_pool');
    }
}

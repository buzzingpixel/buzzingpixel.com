<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210422225322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the licenses table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE licenses (id UUID NOT NULL, user_id UUID DEFAULT NULL, software_id UUID DEFAULT NULL, is_disabled BOOLEAN NOT NULL, major_version VARCHAR(255) NOT NULL, license_key VARCHAR(255) NOT NULL, user_notes VARCHAR(255) NOT NULL, admin_notes VARCHAR(255) NOT NULL, authorized_domains JSON NOT NULL, expires_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7F320F3FA76ED395 ON licenses (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7F320F3FA6CF31DF ON licenses (software_id)');
        $this->addSql('COMMENT ON COLUMN licenses.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN licenses.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN licenses.software_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN licenses.expires_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE licenses ADD CONSTRAINT FK_7F320F3FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE licenses ADD CONSTRAINT FK_7F320F3FA6CF31DF FOREIGN KEY (software_id) REFERENCES software (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE licenses');
    }
}

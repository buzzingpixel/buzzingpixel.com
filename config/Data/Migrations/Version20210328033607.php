<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210328033607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the software and software_versions tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE software (id UUID NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, is_for_sale BOOLEAN NOT NULL, price DOUBLE PRECISION NOT NULL, renewal_price DOUBLE PRECISION NOT NULL, is_subscription BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN software.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE software_versions (id UUID NOT NULL, software_id UUID DEFAULT NULL, major_version VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, download_file VARCHAR(255) NOT NULL, upgrade_price DOUBLE PRECISION NOT NULL, released_on TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EE884C19D7452741 ON software_versions (software_id)');
        $this->addSql('COMMENT ON COLUMN software_versions.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN software_versions.software_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN software_versions.released_on IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE software_versions ADD CONSTRAINT FK_EE884C19D7452741 FOREIGN KEY (software_id) REFERENCES software (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE software_versions DROP CONSTRAINT FK_EE884C19D7452741');
        $this->addSql('DROP TABLE software');
        $this->addSql('DROP TABLE software_versions');
    }
}

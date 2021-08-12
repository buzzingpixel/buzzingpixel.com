<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210812144722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE analytics (id UUID NOT NULL, user_id UUID DEFAULT NULL, cookie_id UUID NOT NULL, logged_in_on_page_load BOOLEAN NOT NULL, date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAC2E688A76ED395 ON analytics (user_id)');
        $this->addSql('COMMENT ON COLUMN analytics.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN analytics.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN analytics.cookie_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN analytics.date IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE analytics ADD CONSTRAINT FK_EAC2E688A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE analytics');
    }
}

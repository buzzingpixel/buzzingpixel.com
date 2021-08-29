<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210829130817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE issue_subscribers (id UUID NOT NULL, issue_id UUID DEFAULT NULL, user_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4D8643085E7AA58C ON issue_subscribers (issue_id)');
        $this->addSql('CREATE INDEX IDX_4D864308A76ED395 ON issue_subscribers (user_id)');
        $this->addSql('COMMENT ON COLUMN issue_subscribers.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN issue_subscribers.issue_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN issue_subscribers.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE issue_subscribers ADD CONSTRAINT FK_4D8643085E7AA58C FOREIGN KEY (issue_id) REFERENCES issues (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue_subscribers ADD CONSTRAINT FK_4D864308A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE issue_subscribers');
    }
}

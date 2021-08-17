<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210817031112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE issue_messages (id UUID NOT NULL, issue_id UUID DEFAULT NULL, user_id UUID DEFAULT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CAA343D5E7AA58C ON issue_messages (issue_id)');
        $this->addSql('CREATE INDEX IDX_CAA343DA76ED395 ON issue_messages (user_id)');
        $this->addSql('COMMENT ON COLUMN issue_messages.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN issue_messages.issue_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN issue_messages.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN issue_messages.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN issue_messages.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE issues (id UUID NOT NULL, user_id UUID DEFAULT NULL, software_id UUID DEFAULT NULL, duplicate_of UUID DEFAULT NULL, issue_number INT NOT NULL, status VARCHAR(255) DEFAULT \'\' NOT NULL, is_public BOOLEAN NOT NULL, software_version VARCHAR(255) NOT NULL, cms_version VARCHAR(255) NOT NULL, php_version VARCHAR(255) NOT NULL, mysql_version VARCHAR(255) NOT NULL, additional_env_details TEXT NOT NULL, private_info TEXT NOT NULL, solution TEXT NOT NULL, solution_file VARCHAR(255) NOT NULL, legacy_solution_file VARCHAR(255) NOT NULL, is_enabled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DA7D7F83A76ED395 ON issues (user_id)');
        $this->addSql('CREATE INDEX IDX_DA7D7F83D7452741 ON issues (software_id)');
        $this->addSql('CREATE INDEX IDX_DA7D7F834463220 ON issues (duplicate_of)');
        $this->addSql('COMMENT ON COLUMN issues.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN issues.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN issues.software_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN issues.duplicate_of IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN issues.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN issues.updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE issue_messages ADD CONSTRAINT FK_CAA343D5E7AA58C FOREIGN KEY (issue_id) REFERENCES issues (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue_messages ADD CONSTRAINT FK_CAA343DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issues ADD CONSTRAINT FK_DA7D7F83A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issues ADD CONSTRAINT FK_DA7D7F83D7452741 FOREIGN KEY (software_id) REFERENCES software (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issues ADD CONSTRAINT FK_DA7D7F834463220 FOREIGN KEY (duplicate_of) REFERENCES issues (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE issue_messages DROP CONSTRAINT FK_CAA343D5E7AA58C');
        $this->addSql('ALTER TABLE issues DROP CONSTRAINT FK_DA7D7F834463220');
        $this->addSql('DROP TABLE issue_messages');
        $this->addSql('DROP TABLE issues');
    }
}

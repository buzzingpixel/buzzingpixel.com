<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210306060456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the user_sessions table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_sessions (id UUID NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, last_touched_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id, user_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7AED7913BF396750 ON user_sessions (id)');
        $this->addSql('COMMENT ON COLUMN user_sessions.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_sessions.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_sessions.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_sessions.last_touched_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_sessions');
    }
}

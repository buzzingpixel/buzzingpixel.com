<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210307052745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the user_password_reset_tokens table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_password_reset_tokens (id UUID NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN user_password_reset_tokens.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_password_reset_tokens.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_password_reset_tokens.created_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_password_reset_tokens');
    }
}

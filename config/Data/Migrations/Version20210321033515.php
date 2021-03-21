<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210321033515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the user_support_profiles table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_support_profiles (id UUID NOT NULL, display_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN user_support_profiles.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE users ADD support_profile_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN users.support_profile_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9EE4ED69A FOREIGN KEY (support_profile_id) REFERENCES user_support_profiles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9EE4ED69A ON users (support_profile_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9EE4ED69A');
        $this->addSql('DROP TABLE user_support_profiles');
        $this->addSql('DROP INDEX UNIQ_1483A5E9EE4ED69A');
        $this->addSql('ALTER TABLE users DROP support_profile_id');
    }
}

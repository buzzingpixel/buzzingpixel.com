<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325132126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the user_billing_profiles table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_billing_profiles (id UUID NOT NULL, billing_name VARCHAR(255) NOT NULL, billing_company VARCHAR(255) NOT NULL, billing_phone VARCHAR(255) NOT NULL, billing_country_region VARCHAR(255) NOT NULL, billing_address VARCHAR(255) NOT NULL, billing_address_continued VARCHAR(255) NOT NULL, billing_city VARCHAR(255) NOT NULL, billing_state_province VARCHAR(255) NOT NULL, billing_postal_code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN user_billing_profiles.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE users ADD billing_profile_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN users.billing_profile_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9409D7D29 FOREIGN KEY (billing_profile_id) REFERENCES user_billing_profiles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9409D7D29 ON users (billing_profile_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9409D7D29');
        $this->addSql('DROP TABLE user_billing_profiles');
        $this->addSql('DROP INDEX UNIQ_1483A5E9409D7D29');
        $this->addSql('ALTER TABLE users DROP billing_profile_id');
    }
}

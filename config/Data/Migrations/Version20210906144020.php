<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210906144020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE licenses ADD is_upgrade BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE licenses ADD has_been_upgraded BOOLEAN DEFAULT \'false\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE licenses DROP is_upgrade');
        $this->addSql('ALTER TABLE licenses DROP has_been_upgraded');
    }
}

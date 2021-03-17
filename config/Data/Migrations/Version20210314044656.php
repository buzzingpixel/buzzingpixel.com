<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210314044656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the schedule_tracking table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE schedule_tracking (id UUID NOT NULL, class_name VARCHAR(255) NOT NULL, is_running BOOLEAN NOT NULL, last_run_start_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, last_run_end_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN schedule_tracking.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN schedule_tracking.last_run_start_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN schedule_tracking.last_run_end_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE schedule_tracking');
    }
}

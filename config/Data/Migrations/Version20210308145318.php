<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210308145318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the queue table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE queue (id UUID NOT NULL, handle VARCHAR(255) NOT NULL, has_started BOOLEAN NOT NULL, is_running BOOLEAN NOT NULL, assume_dead_after TIMESTAMP(0) WITH TIME ZONE NOT NULL, initial_assume_dead_after TIMESTAMP(0) WITH TIME ZONE NOT NULL, is_finished BOOLEAN NOT NULL, finished_due_to_error BOOLEAN NOT NULL, error_message TEXT DEFAULT NULL, percent_complete DOUBLE PRECISION NOT NULL, added_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, finished_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN queue.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN queue.assume_dead_after IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN queue.initial_assume_dead_after IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN queue.added_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN queue.finished_at IS \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE queue');
    }
}

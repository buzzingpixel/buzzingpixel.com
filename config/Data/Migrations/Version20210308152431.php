<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210308152431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the queue_items table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE queue_items (id UUID NOT NULL, queue_id UUID DEFAULT NULL, run_order INT NOT NULL, is_finished BOOLEAN NOT NULL, finished_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, class_name VARCHAR(255) NOT NULL, method_name VARCHAR(255) NOT NULL, context JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B565EE2C477B5BAE ON queue_items (queue_id)');
        $this->addSql('COMMENT ON COLUMN queue_items.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN queue_items.queue_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN queue_items.finished_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE queue_items ADD CONSTRAINT FK_B565EE2C477B5BAE FOREIGN KEY (queue_id) REFERENCES queue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE queue_items DROP CONSTRAINT FK_B565EE2C477B5BAE');
        $this->addSql('DROP TABLE queue_items');
    }
}

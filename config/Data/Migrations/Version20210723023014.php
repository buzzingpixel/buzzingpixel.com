<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210723023014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_7f320f3fa6cf31df');
        $this->addSql('CREATE INDEX IDX_7F320F3FD7452741 ON licenses (software_id)');
        $this->addSql('ALTER INDEX uniq_62809db0a6cf31df RENAME TO UNIQ_62809DB0D7452741');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER INDEX UNIQ_62809DB0D7452741 RENAME TO uniq_62809db0a6cf31df');
        $this->addSql('DROP INDEX IDX_7F320F3FD7452741');
        $this->addSql('CREATE UNIQUE INDEX uniq_7f320f3fa6cf31df ON licenses (software_id)');
    }
}

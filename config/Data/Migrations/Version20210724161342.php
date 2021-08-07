<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210724161342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_62809db0460f904b');
        $this->addSql('DROP INDEX uniq_62809db0d7452741');
        $this->addSql('CREATE INDEX IDX_62809DB0460F904B ON order_items (license_id)');
        $this->addSql('CREATE INDEX IDX_62809DB0D7452741 ON order_items (software_id)');

        $this->addSql('ALTER TABLE orders ALTER stripe_id SET DEFAULT \'\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE orders ALTER stripe_id DROP DEFAULT');

        $this->addSql('DROP INDEX IDX_62809DB0460F904B');
        $this->addSql('DROP INDEX IDX_62809DB0D7452741');
        $this->addSql('CREATE UNIQUE INDEX uniq_62809db0460f904b ON order_items (license_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_62809db0d7452741 ON order_items (software_id)');
    }
}

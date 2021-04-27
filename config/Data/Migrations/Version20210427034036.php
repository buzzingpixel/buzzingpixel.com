<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210427034036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create cart and cart_items tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE cart (id UUID NOT NULL, user_id UUID DEFAULT NULL, last_touched_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7A76ED395 ON cart (user_id)');
        $this->addSql('COMMENT ON COLUMN cart.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cart.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cart.last_touched_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN cart.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE cart_items (id UUID NOT NULL, cart_id UUID DEFAULT NULL, softare_id UUID DEFAULT NULL, quantity INT NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BEF484451AD5CDBF ON cart_items (cart_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BEF48445A6CF31DF ON cart_items (softare_id)');
        $this->addSql('COMMENT ON COLUMN cart_items.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cart_items.cart_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cart_items.softare_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF484451AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF48445A6CF31DF FOREIGN KEY (softare_id) REFERENCES software (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cart_items DROP CONSTRAINT FK_BEF484451AD5CDBF');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_items');
    }
}

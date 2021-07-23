<?php

declare(strict_types=1);

namespace Config\Data\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210422230302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create orders and order_items tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE order_items (id UUID NOT NULL, order_id UUID DEFAULT NULL, license_id UUID DEFAULT NULL, software_id UUID DEFAULT NULL, price INT NOT NULL, original_price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62809DB08D9F6D38 ON order_items (order_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62809DB0460F904B ON order_items (license_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62809DB0A6CF31DF ON order_items (software_id)');
        $this->addSql('COMMENT ON COLUMN order_items.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_items.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_items.license_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_items.software_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE orders (id UUID NOT NULL, user_id UUID DEFAULT NULL, old_order_number VARCHAR(255) NOT NULL, stripe_id VARCHAR(255) NOT NULL, stripe_amount VARCHAR(255) NOT NULL, stripe_balance_transaction VARCHAR(255) NOT NULL, stripe_captured BOOLEAN NOT NULL, stripe_created VARCHAR(255) NOT NULL, stripe_currency VARCHAR(255) NOT NULL, stripe_paid BOOLEAN NOT NULL, sub_total INT NOT NULL, tax INT NOT NULL, total INT NOT NULL, billing_name VARCHAR(255) NOT NULL, billing_company VARCHAR(255) NOT NULL, billing_phone VARCHAR(255) NOT NULL, billing_country_region VARCHAR(255) NOT NULL, billing_address VARCHAR(255) NOT NULL, billing_address_continued VARCHAR(255) NOT NULL, billing_city VARCHAR(255) NOT NULL, billing_state_province VARCHAR(255) NOT NULL, billing_postal_code VARCHAR(255) NOT NULL, order_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON orders (user_id)');
        $this->addSql('COMMENT ON COLUMN orders.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN orders.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN orders.order_date IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB08D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB0460F904B FOREIGN KEY (license_id) REFERENCES licenses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB0A6CF31DF FOREIGN KEY (software_id) REFERENCES software (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE order_items DROP CONSTRAINT FK_62809DB08D9F6D38');
        $this->addSql('ALTER TABLE order_items DROP CONSTRAINT FK_62809DB0460F904B');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE order_items');
    }
}

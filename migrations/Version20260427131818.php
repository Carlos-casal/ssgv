<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260427131818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maestro_material ADD warranty_date DATETIME DEFAULT NULL, DROP warranty_end_date, CHANGE expiration_date expiration_date DATETIME DEFAULT NULL, CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL, CHANGE purchase_date purchase_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE material_batch CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
        $this->addSql('ALTER TABLE material_unit ADD warranty_date DATETIME DEFAULT NULL, DROP warranty_end_date, CHANGE last_used_at last_used_at DATETIME DEFAULT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL, CHANGE purchase_date purchase_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maestro_material ADD warranty_end_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', DROP warranty_date, CHANGE expiration_date expiration_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL, CHANGE purchase_date purchase_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE material_batch CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
        $this->addSql('ALTER TABLE material_unit ADD warranty_end_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', DROP warranty_date, CHANGE last_used_at last_used_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL, CHANGE purchase_date purchase_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}

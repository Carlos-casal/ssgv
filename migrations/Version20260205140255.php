<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205140255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__maestro_material AS SELECT id, name, category, nature, sizing_type, stock, safety_stock, image_path, barcode, description, batch_number, packaging_format, units_per_package, sub_family, expiration_date, supplier, unit_price, iva FROM maestro_material');
        $this->addSql('DROP TABLE maestro_material');
        $this->addSql('CREATE TABLE maestro_material (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, assigned_vehicle_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(50) DEFAULT NULL, nature VARCHAR(20) DEFAULT \'CONSUMIBLE\' NOT NULL, sizing_type VARCHAR(20) DEFAULT NULL, stock INTEGER DEFAULT 0 NOT NULL, safety_stock INTEGER DEFAULT 0 NOT NULL, image_path VARCHAR(255) DEFAULT NULL, barcode VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, batch_number VARCHAR(100) DEFAULT NULL, packaging_format VARCHAR(100) DEFAULT NULL, units_per_package INTEGER DEFAULT NULL, sub_family VARCHAR(100) DEFAULT NULL, expiration_date DATE DEFAULT NULL --(DC2Type:date_immutable)
        , supplier VARCHAR(255) DEFAULT NULL, unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL, serial_number VARCHAR(255) DEFAULT NULL, network_id VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(50) DEFAULT NULL, brand_model VARCHAR(255) DEFAULT NULL, frequency_band VARCHAR(50) DEFAULT NULL, device_type VARCHAR(50) DEFAULT NULL, purchase_date DATE DEFAULT NULL --(DC2Type:date_immutable)
        , warranty_end_date DATE DEFAULT NULL --(DC2Type:date_immutable)
        , has_charger BOOLEAN DEFAULT 0 NOT NULL, has_clip BOOLEAN DEFAULT 0 NOT NULL, has_microphone BOOLEAN DEFAULT 0 NOT NULL, operational_status VARCHAR(50) DEFAULT NULL, battery_status VARCHAR(50) DEFAULT NULL, CONSTRAINT FK_3DACC1916CF274A0 FOREIGN KEY (assigned_vehicle_id) REFERENCES vehicle (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO maestro_material (id, name, category, nature, sizing_type, stock, safety_stock, image_path, barcode, description, batch_number, packaging_format, units_per_package, sub_family, expiration_date, supplier, unit_price, iva) SELECT id, name, category, nature, sizing_type, stock, safety_stock, image_path, barcode, description, batch_number, packaging_format, units_per_package, sub_family, expiration_date, supplier, unit_price, iva FROM __temp__maestro_material');
        $this->addSql('DROP TABLE __temp__maestro_material');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3DACC191D948EE2 ON maestro_material (serial_number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3DACC19134128B91 ON maestro_material (network_id)');
        $this->addSql('CREATE INDEX IDX_3DACC1916CF274A0 ON maestro_material (assigned_vehicle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__maestro_material AS SELECT id, name, category, nature, sizing_type, stock, safety_stock, image_path, barcode, description, batch_number, packaging_format, units_per_package, sub_family, expiration_date, supplier, unit_price, iva FROM maestro_material');
        $this->addSql('DROP TABLE maestro_material');
        $this->addSql('CREATE TABLE maestro_material (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(50) DEFAULT NULL, nature VARCHAR(20) DEFAULT \'CONSUMIBLE\' NOT NULL, sizing_type VARCHAR(20) DEFAULT NULL, stock INTEGER DEFAULT 0 NOT NULL, safety_stock INTEGER DEFAULT 0 NOT NULL, image_path VARCHAR(255) DEFAULT NULL, barcode VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, batch_number VARCHAR(100) DEFAULT NULL, packaging_format VARCHAR(100) DEFAULT NULL, units_per_package INTEGER DEFAULT NULL, sub_family VARCHAR(100) DEFAULT NULL, expiration_date DATE DEFAULT NULL --(DC2Type:date_immutable)
        , supplier VARCHAR(255) DEFAULT NULL, unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL)');
        $this->addSql('INSERT INTO maestro_material (id, name, category, nature, sizing_type, stock, safety_stock, image_path, barcode, description, batch_number, packaging_format, units_per_package, sub_family, expiration_date, supplier, unit_price, iva) SELECT id, name, category, nature, sizing_type, stock, safety_stock, image_path, barcode, description, batch_number, packaging_format, units_per_package, sub_family, expiration_date, supplier, unit_price, iva FROM __temp__maestro_material');
        $this->addSql('DROP TABLE __temp__maestro_material');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260205130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Trazabilidad y Gestión de Ubicaciones - Estructura Completa';
    }

    public function up(Schema $schema): void
    {
        // 1. Create Location table
        $this->addSql('CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, vehicle_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(20) NOT NULL, CONSTRAINT FK_5E9E89CB54531731 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CB54531731 ON location (vehicle_id)');

        // 2. Create LocationReview table
        $this->addSql('CREATE TABLE location_review (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, location_id INTEGER NOT NULL, responsible_id INTEGER NOT NULL, review_date DATE NOT NULL --(DC2Type:date_immutable)
        , result VARCHAR(255) NOT NULL, next_review_date DATE NOT NULL --(DC2Type:date_immutable)
        , CONSTRAINT FK_3F7585EA64D218E5 FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3F7585EA602AD315 FOREIGN KEY (responsible_id) REFERENCES volunteer (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3F7585EA64D218E5 ON location_review (location_id)');
        $this->addSql('CREATE INDEX IDX_3F7585EA602AD315 ON location_review (responsible_id)');

        // 3. Update MaterialStock
        $this->addSql('ALTER TABLE material_stock ADD COLUMN location_id INTEGER NOT NULL REFERENCES location (id)');

        // 4. Update MaterialUnit
        $this->addSql('ALTER TABLE material_unit ADD COLUMN location_id INTEGER REFERENCES location (id)');

        // 5. Update MaterialMovement
        $this->addSql('ALTER TABLE material_movement ADD COLUMN origin_id INTEGER REFERENCES location (id)');
        $this->addSql('ALTER TABLE material_movement ADD COLUMN destination_id INTEGER REFERENCES location (id)');
        $this->addSql('ALTER TABLE material_movement ADD COLUMN responsible_id INTEGER REFERENCES volunteer (id)');
        $this->addSql('ALTER TABLE material_movement ADD COLUMN size VARCHAR(20) DEFAULT NULL');

        // 6. Update MaestroMaterial (Sanitary specialization)
        // Note: Some fields might have been added in previous steps, but we consolidate here.
        // In SQLite, adding multiple columns or renaming is often done via temp tables.
        $this->addSql('CREATE TEMPORARY TABLE __temp__maestro_material AS SELECT id, name, category, nature, sizing_type, stock, safety_stock, image_path, barcode, description, expiration_date, supplier, unit_price, iva FROM maestro_material');
        $this->addSql('DROP TABLE maestro_material');
        $this->addSql('CREATE TABLE maestro_material (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(50) DEFAULT NULL, nature VARCHAR(20) DEFAULT \'CONSUMIBLE\' NOT NULL, sizing_type VARCHAR(20) DEFAULT NULL, stock INTEGER DEFAULT 0 NOT NULL, safety_stock INTEGER DEFAULT 0 NOT NULL, image_path VARCHAR(255) DEFAULT NULL, barcode VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, batch_number VARCHAR(100) DEFAULT NULL, expiration_date DATE DEFAULT NULL --(DC2Type:date_immutable)
        , supplier VARCHAR(255) DEFAULT NULL, unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL, packaging_format VARCHAR(100) DEFAULT NULL, units_per_package INTEGER DEFAULT NULL, sub_family VARCHAR(100) DEFAULT NULL)');
        $this->addSql('INSERT INTO maestro_material (id, name, category, nature, sizing_type, stock, safety_stock, image_path, barcode, description, expiration_date, supplier, unit_price, iva) SELECT id, name, category, nature, sizing_type, stock, safety_stock, image_path, barcode, description, expiration_date, supplier, unit_price, iva FROM __temp__maestro_material');
        $this->addSql('DROP TABLE __temp__maestro_material');
    }

    public function down(Schema $schema): void
    {
        // Not implemented for this manual consolidation
    }
}

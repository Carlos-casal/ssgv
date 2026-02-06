<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260205150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Trazabilidad y Gestión de Ubicaciones - Consolidado';
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
        $this->addSql('ALTER TABLE material_stock ADD COLUMN location_id INTEGER DEFAULT NULL REFERENCES location (id)');

        // 4. Update MaterialUnit
        $this->addSql('ALTER TABLE material_unit ADD COLUMN location_id INTEGER DEFAULT NULL REFERENCES location (id)');

        // 5. Update MaterialMovement
        $this->addSql('ALTER TABLE material_movement ADD COLUMN origin_id INTEGER DEFAULT NULL REFERENCES location (id)');
        $this->addSql('ALTER TABLE material_movement ADD COLUMN destination_id INTEGER DEFAULT NULL REFERENCES location (id)');
        $this->addSql('ALTER TABLE material_movement ADD COLUMN responsible_id INTEGER DEFAULT NULL REFERENCES volunteer (id)');
        $this->addSql('ALTER TABLE material_movement ADD COLUMN size VARCHAR(20) DEFAULT NULL');

        // 6. Update MaestroMaterial - Sanitary & Technical fields
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN batch_number VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN expiration_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN supplier VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN packaging_format VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN units_per_package INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN sub_family VARCHAR(100) DEFAULT NULL');

        // Comms fields
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN serial_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN network_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN phone_number VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN brand_model VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN frequency_band VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN device_type VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN purchase_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN warranty_end_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN has_charger BOOLEAN DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN has_clip BOOLEAN DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN has_microphone BOOLEAN DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN operational_status VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE maestro_material ADD COLUMN battery_status VARCHAR(50) DEFAULT NULL');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_3DACC191D948EE2 ON maestro_material (serial_number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3DACC19134128B91 ON maestro_material (network_id)');
    }

    public function down(Schema $schema): void
    {
    }
}

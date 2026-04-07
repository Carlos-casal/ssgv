<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to ensure all required tables and columns exist for Material management.
 * This addresses 500 errors caused by missing 'supplier', 'iva' or 'material_batch' table.
 */
final class Version20260328000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ensure material_batch table and missing columns (supplier, iva, margin) exist.';
    }

    public function up(Schema $schema): void
    {
        // 1. Ensure material_batch table exists
        $this->addSql('CREATE TABLE IF NOT EXISTS material_batch (
            id INT AUTO_INCREMENT NOT NULL,
            material_id INT NOT NULL,
            batch_number VARCHAR(100) NOT NULL,
            expiration_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\',
            supplier VARCHAR(255) DEFAULT NULL,
            units_per_package INT DEFAULT NULL,
            num_packages INT DEFAULT 0 NOT NULL,
            unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL,
            total_price NUMERIC(10, 2) DEFAULT NULL,
            iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL,
            margin_percentage NUMERIC(5, 2) DEFAULT NULL,
            size VARCHAR(20) DEFAULT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            INDEX IDX_MATERIAL_BATCH_MATERIAL (material_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_MATERIAL_BATCH_MATERIAL FOREIGN KEY (material_id) REFERENCES maestro_material (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // 2. Ensure maestro_material columns exist (Some migrations might have missed them or they were only in SQLite versions)
        // Note: Using IF NOT EXISTS is not standard for ALTER TABLE in MySQL before 8.0.13,
        // but we can use a store procedure or just catch failures. Since we are in a migration,
        // we'll use pure SQL that is likely to work or fail safely.

        $this->addSql('SET @dbname = DATABASE()');
        $this->addSql('SET @tablename = "maestro_material"');
        $this->addSql('SET @columnname = "supplier"');
        $this->addSql('SET @preparedStatement = (SELECT IF(
          (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
          "SELECT 1",
          "ALTER TABLE maestro_material ADD supplier VARCHAR(255) DEFAULT NULL"
        ))');
        $this->addSql('PREPARE stmt FROM @preparedStatement');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');

        $this->addSql('SET @columnname = "iva"');
        $this->addSql('SET @preparedStatement = (SELECT IF(
          (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
          "SELECT 1",
          "ALTER TABLE maestro_material ADD iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL"
        ))');
        $this->addSql('PREPARE stmt FROM @preparedStatement');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');

        $this->addSql('SET @columnname = "margin_percentage"');
        $this->addSql('SET @preparedStatement = (SELECT IF(
          (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
          "SELECT 1",
          "ALTER TABLE maestro_material ADD margin_percentage NUMERIC(5, 2) DEFAULT NULL"
        ))');
        $this->addSql('PREPARE stmt FROM @preparedStatement');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');

        // 3. Ensure material_unit columns exist
        $this->addSql('SET @tablename = "material_unit"');
        $this->addSql('SET @columnname = "supplier"');
        $this->addSql('SET @preparedStatement = (SELECT IF(
          (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
          "SELECT 1",
          "ALTER TABLE material_unit ADD supplier VARCHAR(255) DEFAULT NULL"
        ))');
        $this->addSql('PREPARE stmt FROM @preparedStatement');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');

        $this->addSql('SET @columnname = "iva"');
        $this->addSql('SET @preparedStatement = (SELECT IF(
          (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
          "SELECT 1",
          "ALTER TABLE material_unit ADD iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL"
        ))');
        $this->addSql('PREPARE stmt FROM @preparedStatement');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');

        $this->addSql('SET @columnname = "margin_percentage"');
        $this->addSql('SET @preparedStatement = (SELECT IF(
          (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
          "SELECT 1",
          "ALTER TABLE material_unit ADD margin_percentage NUMERIC(5, 2) DEFAULT NULL"
        ))');
        $this->addSql('PREPARE stmt FROM @preparedStatement');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');

        // 4. Update MaterialStock to have batch_id if missing
        $this->addSql('SET @tablename = "material_stock"');
        $this->addSql('SET @columnname = "batch_id"');
        $this->addSql('SET @preparedStatement = (SELECT IF(
          (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
          "SELECT 1",
          "ALTER TABLE material_stock ADD batch_id INT DEFAULT NULL"
        ))');
        $this->addSql('PREPARE stmt FROM @preparedStatement');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');

        // Note: Constraint check and creation is harder with pure SQL, but this migration
        // aims to fix the "Column not found" error first.
    }

    public function down(Schema $schema): void
    {
    }
}

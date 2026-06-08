<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260604063121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assistance_confirmation ADD assigned_vehicle_id INT DEFAULT NULL, ADD justification LONGTEXT DEFAULT NULL, ADD vehicle_role VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE assistance_confirmation ADD CONSTRAINT FK_A3E138B6CF274A0 FOREIGN KEY (assigned_vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE INDEX IDX_A3E138B6CF274A0 ON assistance_confirmation (assigned_vehicle_id)');
        $this->addSql('ALTER TABLE maestro_material CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
        $this->addSql('ALTER TABLE material_batch CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
        $this->addSql('ALTER TABLE material_unit CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assistance_confirmation DROP FOREIGN KEY FK_A3E138B6CF274A0');
        $this->addSql('DROP INDEX IDX_A3E138B6CF274A0 ON assistance_confirmation');
        $this->addSql('ALTER TABLE assistance_confirmation DROP assigned_vehicle_id, DROP justification, DROP vehicle_role');
        $this->addSql('ALTER TABLE maestro_material CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
        $this->addSql('ALTER TABLE material_batch CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
        $this->addSql('ALTER TABLE material_unit CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260425085636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maestro_material CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
        $this->addSql('ALTER TABLE material_batch CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
        $this->addSql('DROP INDEX UNIQ_88D36166D948EE2 ON material_unit');
        $this->addSql('ALTER TABLE material_unit ADD purchase_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD warranty_end_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD has_charger TINYINT(1) DEFAULT 0 NOT NULL, ADD has_clip TINYINT(1) DEFAULT 0 NOT NULL, ADD has_microphone TINYINT(1) DEFAULT 0 NOT NULL, ADD brand_model VARCHAR(255) DEFAULT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maestro_material CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
        $this->addSql('ALTER TABLE material_batch CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
        $this->addSql('ALTER TABLE material_unit DROP purchase_date, DROP warranty_end_date, DROP has_charger, DROP has_clip, DROP has_microphone, DROP brand_model, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88D36166D948EE2 ON material_unit (serial_number)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313142101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maestro_material CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
        $this->addSql('ALTER TABLE material_unit ADD template_id INT DEFAULT NULL, ADD kit_location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE material_unit ADD CONSTRAINT FK_88D361665DA0FB8 FOREIGN KEY (template_id) REFERENCES kit_template (id)');
        $this->addSql('ALTER TABLE material_unit ADD CONSTRAINT FK_88D3616630FAD39E FOREIGN KEY (kit_location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_88D361665DA0FB8 ON material_unit (template_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88D3616630FAD39E ON material_unit (kit_location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maestro_material CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
        $this->addSql('ALTER TABLE material_unit DROP FOREIGN KEY FK_88D361665DA0FB8');
        $this->addSql('ALTER TABLE material_unit DROP FOREIGN KEY FK_88D3616630FAD39E');
        $this->addSql('DROP INDEX IDX_88D361665DA0FB8 ON material_unit');
        $this->addSql('DROP INDEX UNIQ_88D3616630FAD39E ON material_unit');
        $this->addSql('ALTER TABLE material_unit DROP template_id, DROP kit_location_id');
    }
}

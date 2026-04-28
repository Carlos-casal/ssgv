<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260427183837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kit_template_item ADD COLUMN suggested_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__kit_template_item AS SELECT id, template_id, material_id, quantity FROM kit_template_item');
        $this->addSql('DROP TABLE kit_template_item');
        $this->addSql('CREATE TABLE kit_template_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, template_id INTEGER NOT NULL, material_id INTEGER DEFAULT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_D3A4B8A55DA0FB8 FOREIGN KEY (template_id) REFERENCES kit_template (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D3A4B8A5E308AC6F FOREIGN KEY (material_id) REFERENCES maestro_material (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO kit_template_item (id, template_id, material_id, quantity) SELECT id, template_id, material_id, quantity FROM __temp__kit_template_item');
        $this->addSql('DROP TABLE __temp__kit_template_item');
        $this->addSql('CREATE INDEX IDX_D3A4B8A55DA0FB8 ON kit_template_item (template_id)');
        $this->addSql('CREATE INDEX IDX_D3A4B8A5E308AC6F ON kit_template_item (material_id)');
    }
}

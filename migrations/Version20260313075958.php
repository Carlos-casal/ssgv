<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313075958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE kit_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, container_type VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kit_template_item (id INT AUTO_INCREMENT NOT NULL, template_id INT NOT NULL, material_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_D3A4B8A55DA0FB8 (template_id), INDEX IDX_D3A4B8A5E308AC6F (material_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE kit_template_item ADD CONSTRAINT FK_D3A4B8A55DA0FB8 FOREIGN KEY (template_id) REFERENCES kit_template (id)');
        $this->addSql('ALTER TABLE kit_template_item ADD CONSTRAINT FK_D3A4B8A5E308AC6F FOREIGN KEY (material_id) REFERENCES maestro_material (id)');
        $this->addSql('ALTER TABLE maestro_material CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kit_template_item DROP FOREIGN KEY FK_D3A4B8A55DA0FB8');
        $this->addSql('ALTER TABLE kit_template_item DROP FOREIGN KEY FK_D3A4B8A5E308AC6F');
        $this->addSql('DROP TABLE kit_template');
        $this->addSql('DROP TABLE kit_template_item');
        $this->addSql('ALTER TABLE maestro_material CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
    }
}

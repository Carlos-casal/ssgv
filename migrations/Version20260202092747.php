<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260202092747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE material_unit (id INT AUTO_INCREMENT NOT NULL, material_id INT NOT NULL, serial_number VARCHAR(255) DEFAULT NULL, last_used_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_in_maintenance TINYINT(1) DEFAULT 0 NOT NULL, ptt_status VARCHAR(50) DEFAULT NULL, cover_status VARCHAR(50) DEFAULT NULL, battery_status VARCHAR(50) DEFAULT NULL, INDEX IDX_88D36166E308AC6F (material_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE material_unit ADD CONSTRAINT FK_88D36166E308AC6F FOREIGN KEY (material_id) REFERENCES maestro_material (id)');
        $this->addSql('ALTER TABLE maestro_material ADD nature VARCHAR(20) DEFAULT \'CONSUMIBLE\' NOT NULL, ADD stock INT DEFAULT 0 NOT NULL, ADD safety_stock INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE service_material ADD material_unit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service_material ADD CONSTRAINT FK_85C82EA84927ABA2 FOREIGN KEY (material_unit_id) REFERENCES material_unit (id)');
        $this->addSql('CREATE INDEX IDX_85C82EA84927ABA2 ON service_material (material_unit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service_material DROP FOREIGN KEY FK_85C82EA84927ABA2');
        $this->addSql('ALTER TABLE material_unit DROP FOREIGN KEY FK_88D36166E308AC6F');
        $this->addSql('DROP TABLE material_unit');
        $this->addSql('ALTER TABLE maestro_material DROP nature, DROP stock, DROP safety_stock');
        $this->addSql('DROP INDEX IDX_85C82EA84927ABA2 ON service_material');
        $this->addSql('ALTER TABLE service_material DROP material_unit_id');
    }
}

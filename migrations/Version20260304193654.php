<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260304193654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE material_unit_history (id INT AUTO_INCREMENT NOT NULL, material_unit_id INT NOT NULL, user_id INT DEFAULT NULL, status VARCHAR(50) NOT NULL, reason LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7D8BEA894927ABA2 (material_unit_id), INDEX IDX_7D8BEA89A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE material_unit_history ADD CONSTRAINT FK_7D8BEA894927ABA2 FOREIGN KEY (material_unit_id) REFERENCES material_unit (id)');
        $this->addSql('ALTER TABLE material_unit_history ADD CONSTRAINT FK_7D8BEA89A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE maestro_material DROP sizing_type, DROP packaging_format, CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
        $this->addSql('ALTER TABLE material_unit ADD operational_status VARCHAR(50) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_88D36166D948EE2 ON material_unit (serial_number)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material_unit_history DROP FOREIGN KEY FK_7D8BEA894927ABA2');
        $this->addSql('ALTER TABLE material_unit_history DROP FOREIGN KEY FK_7D8BEA89A76ED395');
        $this->addSql('DROP TABLE material_unit_history');
        $this->addSql('ALTER TABLE maestro_material ADD sizing_type VARCHAR(20) DEFAULT NULL, ADD packaging_format VARCHAR(100) DEFAULT NULL, CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
        $this->addSql('DROP INDEX UNIQ_88D36166D948EE2 ON material_unit');
        $this->addSql('ALTER TABLE material_unit DROP operational_status');
    }
}

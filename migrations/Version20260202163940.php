<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260202163940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE material_movement (id INT AUTO_INCREMENT NOT NULL, material_id INT NOT NULL, user_id INT DEFAULT NULL, size VARCHAR(20) DEFAULT NULL, quantity INT NOT NULL, reason VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B205B155E308AC6F (material_id), INDEX IDX_B205B155A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material_stock (id INT AUTO_INCREMENT NOT NULL, material_id INT NOT NULL, size VARCHAR(20) NOT NULL, quantity INT DEFAULT 0 NOT NULL, INDEX IDX_1DD1FA2EE308AC6F (material_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE material_movement ADD CONSTRAINT FK_B205B155E308AC6F FOREIGN KEY (material_id) REFERENCES maestro_material (id)');
        $this->addSql('ALTER TABLE material_movement ADD CONSTRAINT FK_B205B155A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE material_stock ADD CONSTRAINT FK_1DD1FA2EE308AC6F FOREIGN KEY (material_id) REFERENCES maestro_material (id)');
        $this->addSql('ALTER TABLE maestro_material ADD sizing_type VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE material_unit ADD collective_number VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material_movement DROP FOREIGN KEY FK_B205B155E308AC6F');
        $this->addSql('ALTER TABLE material_movement DROP FOREIGN KEY FK_B205B155A76ED395');
        $this->addSql('ALTER TABLE material_stock DROP FOREIGN KEY FK_1DD1FA2EE308AC6F');
        $this->addSql('DROP TABLE material_movement');
        $this->addSql('DROP TABLE material_stock');
        $this->addSql('ALTER TABLE maestro_material DROP sizing_type');
        $this->addSql('ALTER TABLE material_unit DROP collective_number');
    }
}

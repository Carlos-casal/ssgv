<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923034530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the Fichaje table for individual clock-in/out records.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fichaje (id INT AUTO_INCREMENT NOT NULL, volunteer_service_id INT NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME DEFAULT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_3942A3A4A8A653A (volunteer_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fichaje ADD CONSTRAINT FK_3942A3A4A8A653A FOREIGN KEY (volunteer_service_id) REFERENCES volunteer_service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichaje DROP FOREIGN KEY FK_3942A3A4A8A653A');
        $this->addSql('DROP TABLE fichaje');
    }
}

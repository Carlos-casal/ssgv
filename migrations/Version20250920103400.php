<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250920103400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Fichaje table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fichaje (id INT AUTO_INCREMENT NOT NULL, assistance_confirmation_id INT NOT NULL, type VARCHAR(255) NOT NULL, timestamp DATETIME NOT NULL, note LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2344261C94132478 (assistance_confirmation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fichaje ADD CONSTRAINT FK_2344261C94132478 FOREIGN KEY (assistance_confirmation_id) REFERENCES assistance_confirmation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichaje DROP FOREIGN KEY FK_2344261C94132478');
        $this->addSql('DROP TABLE fichaje');
    }
}

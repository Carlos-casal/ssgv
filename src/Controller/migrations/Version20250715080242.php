<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250715080242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE volunteer_service (id INT AUTO_INCREMENT NOT NULL, volunteer_id INT NOT NULL, service_id INT NOT NULL, attended_at DATETIME NOT NULL, hours DOUBLE PRECISION DEFAULT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_3F7585EA8EFAB6B1 (volunteer_id), INDEX IDX_3F7585EAED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE volunteer_service ADD CONSTRAINT FK_3F7585EA8EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id)');
        $this->addSql('ALTER TABLE volunteer_service ADD CONSTRAINT FK_3F7585EAED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer_service DROP FOREIGN KEY FK_3F7585EA8EFAB6B1');
        $this->addSql('ALTER TABLE volunteer_service DROP FOREIGN KEY FK_3F7585EAED5CA9E6');
        $this->addSql('DROP TABLE volunteer_service');
    }
}

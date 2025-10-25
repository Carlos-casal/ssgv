<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250715094739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assistance_confirmation (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, volunteer_id INT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_A3E138BED5CA9E6 (service_id), INDEX IDX_A3E138B8EFAB6B1 (volunteer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assistance_confirmation ADD CONSTRAINT FK_A3E138BED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE assistance_confirmation ADD CONSTRAINT FK_A3E138B8EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assistance_confirmation DROP FOREIGN KEY FK_A3E138BED5CA9E6');
        $this->addSql('ALTER TABLE assistance_confirmation DROP FOREIGN KEY FK_A3E138B8EFAB6B1');
        $this->addSql('DROP TABLE assistance_confirmation');
    }
}

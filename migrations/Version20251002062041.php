<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251002062041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update Vehicle entity with new fields and remove year';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle ADD photo VARCHAR(255) DEFAULT NULL, ADD alias VARCHAR(255) DEFAULT NULL, ADD registration_date DATE DEFAULT NULL, ADD fuel_type VARCHAR(255) DEFAULT NULL, ADD type VARCHAR(255) DEFAULT NULL, ADD next_revision_date DATE DEFAULT NULL, ADD insurance_due_date DATE DEFAULT NULL, ADD cabin_type VARCHAR(255) DEFAULT NULL, ADD resources VARCHAR(255) DEFAULT NULL, DROP year');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle ADD year INT NOT NULL, DROP photo, DROP alias, DROP registration_date, DROP fuel_type, DROP type, DROP next_revision_date, DROP insurance_due_date, DROP cabin_type, DROP resources');
    }
}
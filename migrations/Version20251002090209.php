<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Creates the vehicle and fuel_type tables from scratch.
 */
final class Version20251002090209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the vehicle and fuel_type tables and their relationship.';
    }

    public function up(Schema $schema): void
    {
        // Creates the fuel_type table
        $this->addSql('CREATE TABLE fuel_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4A37B085E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Creates the vehicle table with all fields
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, fuel_type_id INT DEFAULT NULL, make VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, license_plate VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, alias VARCHAR(255) DEFAULT NULL, registration_date DATE DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, next_revision_date DATE DEFAULT NULL, insurance_due_date DATE DEFAULT NULL, cabin_type VARCHAR(255) DEFAULT NULL, resources VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1B80E486A148A4F4 (license_plate), INDEX IDX_1B80E4861A7389E5 (fuel_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Creates the foreign key relationship
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4861A7389E5 FOREIGN KEY (fuel_type_id) REFERENCES fuel_type (id)');
    }

    public function down(Schema $schema): void
    {
        // Drops the tables in reverse order to respect constraints
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4861A7389E5');
        $this->addSql('DROP TABLE fuel_type');
        $this->addSql('DROP TABLE vehicle');
    }
}
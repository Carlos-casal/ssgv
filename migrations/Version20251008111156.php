<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251008111156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer ADD other_allergies LONGTEXT DEFAULT NULL, DROP specialization, CHANGE last_name last_name VARCHAR(255) NOT NULL, CHANGE dni dni VARCHAR(15) NOT NULL, CHANGE date_of_birth date_of_birth DATE NOT NULL, CHANGE street_type street_type VARCHAR(50) NOT NULL, CHANGE address address VARCHAR(255) NOT NULL, CHANGE postal_code postal_code VARCHAR(10) NOT NULL, CHANGE province province VARCHAR(100) NOT NULL, CHANGE city city VARCHAR(100) NOT NULL, CHANGE contact_person1 contact_person1 VARCHAR(255) NOT NULL, CHANGE contact_phone1 contact_phone1 VARCHAR(20) NOT NULL, CHANGE motivation motivation LONGTEXT NOT NULL, CHANGE how_known how_known VARCHAR(255) NOT NULL, CHANGE allergies food_allergies LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer ADD allergies LONGTEXT DEFAULT NULL, ADD specialization VARCHAR(255) NOT NULL, DROP food_allergies, DROP other_allergies, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE dni dni VARCHAR(15) DEFAULT NULL, CHANGE date_of_birth date_of_birth DATE DEFAULT NULL, CHANGE street_type street_type VARCHAR(50) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(10) DEFAULT NULL, CHANGE province province VARCHAR(100) DEFAULT NULL, CHANGE city city VARCHAR(100) DEFAULT NULL, CHANGE contact_person1 contact_person1 VARCHAR(255) DEFAULT NULL, CHANGE contact_phone1 contact_phone1 VARCHAR(20) DEFAULT NULL, CHANGE motivation motivation LONGTEXT DEFAULT NULL, CHANGE how_known how_known VARCHAR(255) DEFAULT NULL');
    }
}

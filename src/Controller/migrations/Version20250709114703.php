<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250709114703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, numeration VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, registration_limit_date DATETIME DEFAULT NULL, time_at_base TIME DEFAULT NULL, departure_time TIME DEFAULT NULL, max_attendees INT DEFAULT NULL, type VARCHAR(50) DEFAULT NULL, category VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, recipients JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', eys VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E19D9AD2989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) NOT NULL, dni VARCHAR(15) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, street_type VARCHAR(50) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, province VARCHAR(100) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, contact_person1 VARCHAR(255) DEFAULT NULL, contact_phone1 VARCHAR(20) DEFAULT NULL, contact_person2 VARCHAR(255) DEFAULT NULL, contact_phone2 VARCHAR(20) DEFAULT NULL, allergies LONGTEXT DEFAULT NULL, profession VARCHAR(100) DEFAULT NULL, employment_status VARCHAR(100) DEFAULT NULL, driving_licenses JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', driving_license_expiry_date DATE DEFAULT NULL, languages LONGTEXT DEFAULT NULL, motivation LONGTEXT DEFAULT NULL, how_known VARCHAR(255) DEFAULT NULL, has_volunteered_before TINYINT(1) DEFAULT NULL, previous_volunteering_institutions LONGTEXT DEFAULT NULL, other_qualifications LONGTEXT DEFAULT NULL, navigation_licenses JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', specific_qualifications JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', role VARCHAR(100) NOT NULL, status VARCHAR(20) NOT NULL, join_date DATETIME NOT NULL, specialization VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5140DEDB7F8F253B (dni), UNIQUE INDEX UNIQ_5140DEDBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDBA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDBA76ED395');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE volunteer');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

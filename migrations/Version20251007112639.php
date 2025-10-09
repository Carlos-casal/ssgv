<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251007112639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assistance_confirmation (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, volunteer_id INT NOT NULL, status VARCHAR(255) DEFAULT \'not_attending\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_fichaje_responsible TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_A3E138BED5CA9E6 (service_id), INDEX IDX_A3E138B8EFAB6B1 (volunteer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fichaje (id INT AUTO_INCREMENT NOT NULL, volunteer_service_id INT NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME DEFAULT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_912E9DAFA4AF79C4 (volunteer_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fuel_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9CA10F385E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, is_used TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_F11D61A25F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, numeration VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, registration_limit_date DATETIME DEFAULT NULL, time_at_base TIME DEFAULT NULL, departure_time TIME DEFAULT NULL, max_attendees INT DEFAULT NULL, type VARCHAR(50) DEFAULT NULL, category VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, recipients JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', collaboration_with_other_services TINYINT(1) DEFAULT 0 NOT NULL, locality VARCHAR(255) DEFAULT NULL, requester VARCHAR(255) DEFAULT NULL, afluencia VARCHAR(255) DEFAULT NULL, num_svb INT DEFAULT NULL, num_sva INT DEFAULT NULL, num_svae INT DEFAULT NULL, num_doctors INT DEFAULT NULL, num_nurses INT DEFAULT NULL, has_field_hospital TINYINT(1) DEFAULT NULL, tasks LONGTEXT DEFAULT NULL, has_provisions TINYINT(1) DEFAULT NULL, whatsapp_message LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_E19D9AD2989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, setting_key VARCHAR(255) NOT NULL, setting_value VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9F74B8985FA1E697 (setting_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, fuel_type_id INT DEFAULT NULL, make VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, license_plate VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, alias VARCHAR(255) DEFAULT NULL, registration_date DATE DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, next_revision_date DATE DEFAULT NULL, insurance_due_date DATE DEFAULT NULL, cabin_type VARCHAR(255) DEFAULT NULL, resources VARCHAR(255) DEFAULT NULL, is_out_of_service TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1B80E486F5AA79D0 (license_plate), INDEX IDX_1B80E4866A70FE35 (fuel_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) NOT NULL, dni VARCHAR(15) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, street_type VARCHAR(50) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, province VARCHAR(100) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, contact_person1 VARCHAR(255) DEFAULT NULL, contact_phone1 VARCHAR(20) DEFAULT NULL, contact_person2 VARCHAR(255) DEFAULT NULL, contact_phone2 VARCHAR(20) DEFAULT NULL, allergies LONGTEXT DEFAULT NULL, profession VARCHAR(100) DEFAULT NULL, employment_status VARCHAR(100) DEFAULT NULL, driving_licenses JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', driving_license_expiry_date DATE DEFAULT NULL, languages LONGTEXT DEFAULT NULL, motivation LONGTEXT DEFAULT NULL, how_known VARCHAR(255) DEFAULT NULL, has_volunteered_before TINYINT(1) NOT NULL, previous_volunteering_institutions LONGTEXT DEFAULT NULL, other_qualifications LONGTEXT DEFAULT NULL, navigation_licenses JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', specific_qualifications JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', role VARCHAR(100) NOT NULL, status VARCHAR(20) NOT NULL, join_date DATETIME NOT NULL, status_change_date DATETIME DEFAULT NULL, specialization VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, numero_identificacion VARCHAR(255) DEFAULT NULL, indicativo VARCHAR(255) DEFAULT NULL, habilitado_conducir TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_5140DEDB7F8F253B (dni), UNIQUE INDEX UNIQ_5140DEDB3D57FC59 (indicativo), UNIQUE INDEX UNIQ_5140DEDBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteer_service (id INT AUTO_INCREMENT NOT NULL, volunteer_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_3F7585EA8EFAB6B1 (volunteer_id), INDEX IDX_3F7585EAED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assistance_confirmation ADD CONSTRAINT FK_A3E138BED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE assistance_confirmation ADD CONSTRAINT FK_A3E138B8EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id)');
        $this->addSql('ALTER TABLE fichaje ADD CONSTRAINT FK_912E9DAFA4AF79C4 FOREIGN KEY (volunteer_service_id) REFERENCES volunteer_service (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4866A70FE35 FOREIGN KEY (fuel_type_id) REFERENCES fuel_type (id)');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDBA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE volunteer_service ADD CONSTRAINT FK_3F7585EA8EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id)');
        $this->addSql('ALTER TABLE volunteer_service ADD CONSTRAINT FK_3F7585EAED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assistance_confirmation DROP FOREIGN KEY FK_A3E138BED5CA9E6');
        $this->addSql('ALTER TABLE assistance_confirmation DROP FOREIGN KEY FK_A3E138B8EFAB6B1');
        $this->addSql('ALTER TABLE fichaje DROP FOREIGN KEY FK_912E9DAFA4AF79C4');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4866A70FE35');
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDBA76ED395');
        $this->addSql('ALTER TABLE volunteer_service DROP FOREIGN KEY FK_3F7585EA8EFAB6B1');
        $this->addSql('ALTER TABLE volunteer_service DROP FOREIGN KEY FK_3F7585EAED5CA9E6');
        $this->addSql('DROP TABLE assistance_confirmation');
        $this->addSql('DROP TABLE fichaje');
        $this->addSql('DROP TABLE fuel_type');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE volunteer');
        $this->addSql('DROP TABLE volunteer_service');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

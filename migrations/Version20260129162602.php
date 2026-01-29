<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260129162602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assistance_confirmation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, service_id INTEGER NOT NULL, volunteer_id INTEGER NOT NULL, status VARCHAR(255) DEFAULT \'not_attending\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_fichaje_responsible BOOLEAN DEFAULT 0 NOT NULL, CONSTRAINT FK_A3E138BED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A3E138B8EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A3E138BED5CA9E6 ON assistance_confirmation (service_id)');
        $this->addSql('CREATE INDEX IDX_A3E138B8EFAB6B1 ON assistance_confirmation (volunteer_id)');
        $this->addSql('CREATE TABLE fichaje (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, volunteer_service_id INTEGER NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME DEFAULT NULL, notes CLOB DEFAULT NULL, CONSTRAINT FK_912E9DAFA4AF79C4 FOREIGN KEY (volunteer_service_id) REFERENCES volunteer_service (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_912E9DAFA4AF79C4 ON fichaje (volunteer_service_id)');
        $this->addSql('CREATE TABLE fuel_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9CA10F385E237E06 ON fuel_type (name)');
        $this->addSql('CREATE TABLE invitation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, is_used BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F11D61A25F37A13B ON invitation (token)');
        $this->addSql('CREATE TABLE maestro_material (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(50) DEFAULT NULL)');
        $this->addSql('CREATE TABLE service (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER DEFAULT NULL, category_id INTEGER DEFAULT NULL, subcategory_id INTEGER DEFAULT NULL, numeration VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, registration_limit_date DATETIME DEFAULT NULL, time_at_base TIME DEFAULT NULL, departure_time TIME DEFAULT NULL, max_attendees INTEGER DEFAULT NULL, description CLOB DEFAULT NULL, recipients CLOB DEFAULT NULL --(DC2Type:json)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , collaboration_with_other_services BOOLEAN DEFAULT 0 NOT NULL, locality VARCHAR(255) DEFAULT NULL, requester VARCHAR(255) DEFAULT NULL, afluencia VARCHAR(255) DEFAULT NULL, num_svb INTEGER DEFAULT NULL, num_sva INTEGER DEFAULT NULL, num_svae INTEGER DEFAULT NULL, num_vir INTEGER DEFAULT NULL, num_doctors INTEGER DEFAULT NULL, num_nurses INTEGER DEFAULT NULL, num_tes INTEGER DEFAULT NULL, num_tts INTEGER DEFAULT NULL, num_due INTEGER DEFAULT NULL, has_field_hospital BOOLEAN DEFAULT NULL, tasks CLOB DEFAULT NULL, has_provisions BOOLEAN DEFAULT NULL, whatsapp_message CLOB DEFAULT NULL, is_archived BOOLEAN NOT NULL, CONSTRAINT FK_E19D9AD2C54C8C93 FOREIGN KEY (type_id) REFERENCES service_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E19D9AD212469DE2 FOREIGN KEY (category_id) REFERENCES service_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E19D9AD25DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES service_subcategory (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E19D9AD2989D9B62 ON service (slug)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2C54C8C93 ON service (type_id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD212469DE2 ON service (category_id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD25DC6FE57 ON service (subcategory_id)');
        $this->addSql('CREATE TABLE service_vehicles (service_id INTEGER NOT NULL, vehicle_id INTEGER NOT NULL, PRIMARY KEY(service_id, vehicle_id), CONSTRAINT FK_E6B832C7ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E6B832C7545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E6B832C7ED5CA9E6 ON service_vehicles (service_id)');
        $this->addSql('CREATE INDEX IDX_E6B832C7545317D1 ON service_vehicles (vehicle_id)');
        $this->addSql('CREATE TABLE service_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(50) DEFAULT NULL, CONSTRAINT FK_FF3A42FCC54C8C93 FOREIGN KEY (type_id) REFERENCES service_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FF3A42FC77153098 ON service_category (code)');
        $this->addSql('CREATE INDEX IDX_FF3A42FCC54C8C93 ON service_category (type_id)');
        $this->addSql('CREATE TABLE service_material (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, service_id INTEGER NOT NULL, material_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_85C82EA8ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_85C82EA8E308AC6F FOREIGN KEY (material_id) REFERENCES maestro_material (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_85C82EA8ED5CA9E6 ON service_material (service_id)');
        $this->addSql('CREATE INDEX IDX_85C82EA8E308AC6F ON service_material (material_id)');
        $this->addSql('CREATE TABLE service_subcategory (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(50) DEFAULT NULL, CONSTRAINT FK_C14682E412469DE2 FOREIGN KEY (category_id) REFERENCES service_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C14682E477153098 ON service_subcategory (code)');
        $this->addSql('CREATE INDEX IDX_C14682E412469DE2 ON service_subcategory (category_id)');
        $this->addSql('CREATE TABLE service_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(50) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_429DE3C577153098 ON service_type (code)');
        $this->addSql('CREATE TABLE setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, setting_key VARCHAR(255) NOT NULL, setting_value VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9F74B8985FA1E697 ON setting (setting_key)');
        $this->addSql('CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, reset_token VARCHAR(100) DEFAULT NULL, reset_token_expires_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE vehicle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fuel_type_id INTEGER DEFAULT NULL, make VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, license_plate VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, alias VARCHAR(255) DEFAULT NULL, registration_date DATE DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, next_revision_date DATE DEFAULT NULL, insurance_due_date DATE DEFAULT NULL, cabin_type VARCHAR(255) DEFAULT NULL, resources VARCHAR(255) DEFAULT NULL, is_out_of_service BOOLEAN NOT NULL, CONSTRAINT FK_1B80E4866A70FE35 FOREIGN KEY (fuel_type_id) REFERENCES fuel_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B80E486F5AA79D0 ON vehicle (license_plate)');
        $this->addSql('CREATE INDEX IDX_1B80E4866A70FE35 ON vehicle (fuel_type_id)');
        $this->addSql('CREATE TABLE volunteer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, dni VARCHAR(15) NOT NULL, date_of_birth DATE NOT NULL, street_type VARCHAR(50) NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(10) NOT NULL, province VARCHAR(100) NOT NULL, city VARCHAR(100) NOT NULL, contact_person1 VARCHAR(255) NOT NULL, contact_phone1 VARCHAR(20) NOT NULL, contact_person2 VARCHAR(255) DEFAULT NULL, contact_phone2 VARCHAR(20) DEFAULT NULL, food_allergies CLOB DEFAULT NULL, other_allergies CLOB DEFAULT NULL, profession VARCHAR(100) DEFAULT NULL, employment_status VARCHAR(100) DEFAULT NULL, driving_licenses CLOB DEFAULT NULL --(DC2Type:json)
        , driving_license_expiry_date DATE DEFAULT NULL, languages CLOB DEFAULT NULL, motivation CLOB NOT NULL, how_known VARCHAR(255) NOT NULL, has_volunteered_before BOOLEAN NOT NULL, previous_volunteering_institutions CLOB DEFAULT NULL, other_qualifications CLOB DEFAULT NULL, navigation_licenses CLOB DEFAULT NULL --(DC2Type:json)
        , specific_qualifications CLOB DEFAULT NULL --(DC2Type:json)
        , role VARCHAR(100) NOT NULL, status VARCHAR(20) NOT NULL, join_date DATETIME NOT NULL, status_change_date DATETIME DEFAULT NULL, profile_picture VARCHAR(255) DEFAULT NULL, indicativo VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_5140DEDBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5140DEDB7F8F253B ON volunteer (dni)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5140DEDB3D57FC59 ON volunteer (indicativo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5140DEDBA76ED395 ON volunteer (user_id)');
        $this->addSql('CREATE TABLE volunteer_service (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, volunteer_id INTEGER NOT NULL, service_id INTEGER NOT NULL, CONSTRAINT FK_3F7585EA8EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3F7585EAED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3F7585EA8EFAB6B1 ON volunteer_service (volunteer_id)');
        $this->addSql('CREATE INDEX IDX_3F7585EAED5CA9E6 ON volunteer_service (service_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE assistance_confirmation');
        $this->addSql('DROP TABLE fichaje');
        $this->addSql('DROP TABLE fuel_type');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE maestro_material');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE service_vehicles');
        $this->addSql('DROP TABLE service_category');
        $this->addSql('DROP TABLE service_material');
        $this->addSql('DROP TABLE service_subcategory');
        $this->addSql('DROP TABLE service_type');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE volunteer');
        $this->addSql('DROP TABLE volunteer_service');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

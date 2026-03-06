<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306105949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service ADD COLUMN estimated_people INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__service AS SELECT id, type_id, category_id, subcategory_id, numeration, title, slug, start_date, end_date, registration_limit_date, time_at_base, departure_time, max_attendees, description, recipients, created_at, updated_at, collaboration_with_other_services, locality, requester, afluencia, num_svb, num_sva, num_colectiva, num_svae, num_vir, num_doctors, num_nurses, num_tes, num_tts, num_due, has_field_hospital, tasks, has_provisions, whatsapp_message, is_archived FROM service');
        $this->addSql('DROP TABLE service');
        $this->addSql('CREATE TABLE service (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type_id INTEGER DEFAULT NULL, category_id INTEGER DEFAULT NULL, subcategory_id INTEGER DEFAULT NULL, numeration VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, registration_limit_date DATETIME DEFAULT NULL, time_at_base TIME DEFAULT NULL, departure_time TIME DEFAULT NULL, max_attendees INTEGER DEFAULT NULL, description CLOB DEFAULT NULL, recipients CLOB DEFAULT NULL --(DC2Type:json)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , collaboration_with_other_services BOOLEAN DEFAULT 0 NOT NULL, locality VARCHAR(255) DEFAULT NULL, requester VARCHAR(255) DEFAULT NULL, afluencia VARCHAR(255) DEFAULT NULL, num_svb INTEGER DEFAULT NULL, num_sva INTEGER DEFAULT NULL, num_colectiva INTEGER DEFAULT NULL, num_svae INTEGER DEFAULT NULL, num_vir INTEGER DEFAULT NULL, num_doctors INTEGER DEFAULT NULL, num_nurses INTEGER DEFAULT NULL, num_tes INTEGER DEFAULT NULL, num_tts INTEGER DEFAULT NULL, num_due INTEGER DEFAULT NULL, has_field_hospital BOOLEAN DEFAULT NULL, tasks CLOB DEFAULT NULL, has_provisions BOOLEAN DEFAULT NULL, whatsapp_message CLOB DEFAULT NULL, is_archived BOOLEAN NOT NULL, CONSTRAINT FK_E19D9AD2C54C8C93 FOREIGN KEY (type_id) REFERENCES service_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E19D9AD212469DE2 FOREIGN KEY (category_id) REFERENCES service_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E19D9AD25DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES service_subcategory (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO service (id, type_id, category_id, subcategory_id, numeration, title, slug, start_date, end_date, registration_limit_date, time_at_base, departure_time, max_attendees, description, recipients, created_at, updated_at, collaboration_with_other_services, locality, requester, afluencia, num_svb, num_sva, num_colectiva, num_svae, num_vir, num_doctors, num_nurses, num_tes, num_tts, num_due, has_field_hospital, tasks, has_provisions, whatsapp_message, is_archived) SELECT id, type_id, category_id, subcategory_id, numeration, title, slug, start_date, end_date, registration_limit_date, time_at_base, departure_time, max_attendees, description, recipients, created_at, updated_at, collaboration_with_other_services, locality, requester, afluencia, num_svb, num_sva, num_colectiva, num_svae, num_vir, num_doctors, num_nurses, num_tes, num_tts, num_due, has_field_hospital, tasks, has_provisions, whatsapp_message, is_archived FROM __temp__service');
        $this->addSql('DROP TABLE __temp__service');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E19D9AD2D7C0263 ON service (numeration)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E19D9AD2989D9B62 ON service (slug)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2C54C8C93 ON service (type_id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD212469DE2 ON service (category_id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD25DC6FE57 ON service (subcategory_id)');
    }
}

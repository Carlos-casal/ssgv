<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260603162000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__assistance_confirmation AS SELECT id, service_id, volunteer_id, assigned_vehicle_id, status, created_at, updated_at, is_fichaje_responsible, justification, vehicle_role, is_sanitario, is_socorrista, is_conductor FROM assistance_confirmation');
        $this->addSql('DROP TABLE assistance_confirmation');
        $this->addSql('CREATE TABLE assistance_confirmation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, service_id INTEGER NOT NULL, volunteer_id INTEGER NOT NULL, assigned_vehicle_id INTEGER DEFAULT NULL, status VARCHAR(255) DEFAULT \'not_attending\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_fichaje_responsible BOOLEAN DEFAULT 0 NOT NULL, justification CLOB DEFAULT NULL, vehicle_role VARCHAR(50) DEFAULT NULL, is_sanitario BOOLEAN DEFAULT 0 NOT NULL, is_socorrista BOOLEAN DEFAULT 0 NOT NULL, is_conductor BOOLEAN DEFAULT 0 NOT NULL, CONSTRAINT FK_A3E138BED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A3E138B8EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A3E138B6CF274A0 FOREIGN KEY (assigned_vehicle_id) REFERENCES vehicle (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO assistance_confirmation (id, service_id, volunteer_id, assigned_vehicle_id, status, created_at, updated_at, is_fichaje_responsible, justification, vehicle_role, is_sanitario, is_socorrista, is_conductor) SELECT id, service_id, volunteer_id, assigned_vehicle_id, status, created_at, updated_at, is_fichaje_responsible, justification, vehicle_role, is_sanitario, is_socorrista, is_conductor FROM __temp__assistance_confirmation');
        $this->addSql('DROP TABLE __temp__assistance_confirmation');
        $this->addSql('CREATE INDEX IDX_A3E138B6CF274A0 ON assistance_confirmation (assigned_vehicle_id)');
        $this->addSql('CREATE INDEX IDX_A3E138B8EFAB6B1 ON assistance_confirmation (volunteer_id)');
        $this->addSql('CREATE INDEX IDX_A3E138BED5CA9E6 ON assistance_confirmation (service_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__assistance_confirmation AS SELECT id, service_id, volunteer_id, assigned_vehicle_id, status, created_at, updated_at, is_fichaje_responsible, justification, vehicle_role FROM assistance_confirmation');
        $this->addSql('DROP TABLE assistance_confirmation');
        $this->addSql('CREATE TABLE assistance_confirmation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, service_id INTEGER NOT NULL, volunteer_id INTEGER NOT NULL, assigned_vehicle_id INTEGER DEFAULT NULL, status VARCHAR(255) DEFAULT \'not_attending\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_fichaje_responsible BOOLEAN DEFAULT 0 NOT NULL, justification CLOB DEFAULT NULL, vehicle_role VARCHAR(50) DEFAULT NULL)');
        $this->addSql('INSERT INTO assistance_confirmation (id, service_id, volunteer_id, assigned_vehicle_id, status, created_at, updated_at, is_fichaje_responsible, justification, vehicle_role) SELECT id, service_id, volunteer_id, assigned_vehicle_id, status, created_at, updated_at, is_fichaje_responsible, justification, vehicle_role FROM __temp__assistance_confirmation');
        $this->addSql('DROP TABLE __temp__assistance_confirmation');
        $this->addSql('CREATE INDEX IDX_A3E138B6CF274A0 ON assistance_confirmation (assigned_vehicle_id)');
        $this->addSql('CREATE INDEX IDX_A3E138B8EFAB6B1 ON assistance_confirmation (volunteer_id)');
        $this->addSql('CREATE INDEX IDX_A3E138BED5CA9E6 ON assistance_confirmation (service_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104081711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE battery DROP FOREIGN KEY FK_D02EF4AE78F8F175');
        $this->addSql('ALTER TABLE mobile DROP FOREIGN KEY FK_3C7323E0B132A4EB');
        $this->addSql('ALTER TABLE ptt DROP FOREIGN KEY FK_B78BB89578F8F175');
        $this->addSql('DROP TABLE battery');
        $this->addSql('DROP TABLE mobile');
        $this->addSql('DROP TABLE phone_model');
        $this->addSql('DROP TABLE ptt');
        $this->addSql('DROP TABLE talkie');
        $this->addSql('ALTER TABLE service ADD type_id INT DEFAULT NULL, ADD category_id INT DEFAULT NULL, DROP type, DROP category, CHANGE is_archived is_archived TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2C54C8C93 FOREIGN KEY (type_id) REFERENCES service_type (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD212469DE2 FOREIGN KEY (category_id) REFERENCES service_category (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2C54C8C93 ON service (type_id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD212469DE2 ON service (category_id)');
        $this->addSql('ALTER TABLE volunteer DROP habilitado_conducir');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD212469DE2');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2C54C8C93');
        $this->addSql('CREATE TABLE battery (id INT AUTO_INCREMENT NOT NULL, talkie_id INT DEFAULT NULL, ref VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, serial_no VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_D02EF4AE78F8F175 (talkie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE mobile (id INT AUTO_INCREMENT NOT NULL, phone_model_id INT NOT NULL, imei VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ext_corta VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ext_larga VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_3C7323E0B132A4EB (phone_model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE phone_model (id INT AUTO_INCREMENT NOT NULL, make VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, model VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ptt (id INT AUTO_INCREMENT NOT NULL, talkie_id INT DEFAULT NULL, ref VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, serial_no VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_B78BB89578F8F175 (talkie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE talkie (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, serial_no VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hw_version VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, modelo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, tei VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE battery ADD CONSTRAINT FK_D02EF4AE78F8F175 FOREIGN KEY (talkie_id) REFERENCES talkie (id)');
        $this->addSql('ALTER TABLE mobile ADD CONSTRAINT FK_3C7323E0B132A4EB FOREIGN KEY (phone_model_id) REFERENCES phone_model (id)');
        $this->addSql('ALTER TABLE ptt ADD CONSTRAINT FK_B78BB89578F8F175 FOREIGN KEY (talkie_id) REFERENCES talkie (id)');
        $this->addSql('DROP TABLE service_category');
        $this->addSql('DROP TABLE service_type');
        $this->addSql('DROP INDEX IDX_E19D9AD2C54C8C93 ON service');
        $this->addSql('DROP INDEX IDX_E19D9AD212469DE2 ON service');
        $this->addSql('ALTER TABLE service ADD type VARCHAR(50) DEFAULT NULL, ADD category VARCHAR(50) DEFAULT NULL, DROP type_id, DROP category_id, CHANGE is_archived is_archived TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE volunteer ADD habilitado_conducir TINYINT(1) NOT NULL');
    }
}

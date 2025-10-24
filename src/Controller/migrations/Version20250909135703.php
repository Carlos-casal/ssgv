<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250909135703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assistance_confirmation DROP check_in_time, DROP check_out_time');
        $this->addSql('ALTER TABLE service ADD whatsapp_message LONGTEXT DEFAULT NULL, DROP has_supplies, DROP sva_count, DROP svb_count, DROP responsible_person');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assistance_confirmation ADD check_in_time DATETIME DEFAULT NULL, ADD check_out_time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD has_supplies TINYINT(1) DEFAULT 0 NOT NULL, ADD sva_count INT DEFAULT NULL, ADD svb_count INT DEFAULT NULL, ADD responsible_person VARCHAR(255) DEFAULT NULL, DROP whatsapp_message');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250827043100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add has_supplies, sva_count, svb_count, and responsible_person to service table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service ADD has_supplies TINYINT(1) DEFAULT 0 NOT NULL, ADD sva_count INT DEFAULT NULL, ADD svb_count INT DEFAULT NULL, ADD responsible_person VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP has_supplies, DROP sva_count, DROP svb_count, DROP responsible_person');
    }
}

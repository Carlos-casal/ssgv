<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923035300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Removes obsolete startTime, endTime, duration, and notes columns from the volunteer_service table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer_service DROP start_time, DROP end_time, DROP notes, DROP duration');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer_service ADD start_time DATETIME DEFAULT NULL, ADD end_time DATETIME DEFAULT NULL, ADD notes LONGTEXT DEFAULT NULL, ADD duration INT DEFAULT NULL');
    }
}

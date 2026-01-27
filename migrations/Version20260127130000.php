<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Adds new personnel fields to the service table.
 */
final class Version20260127130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add num_tes, num_tts, and num_due fields to service table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE service ADD COLUMN num_tes INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD COLUMN num_tts INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD COLUMN num_due INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE service DROP COLUMN num_tes');
        $this->addSql('ALTER TABLE service DROP COLUMN num_tts');
        $this->addSql('ALTER TABLE service DROP COLUMN num_due');
    }
}

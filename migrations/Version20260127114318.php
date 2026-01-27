<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Adds new personnel fields to the service table.
 */
final class Version20260127114318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add num_tes, num_tts, and num_due fields to service table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE service ADD num_tes INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD num_tts INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD num_due INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE service DROP num_tes');
        $this->addSql('ALTER TABLE service DROP num_tts');
        $this->addSql('ALTER TABLE service DROP num_due');
    }
}

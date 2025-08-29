<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250829043900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add check_in_time and check_out_time to assistance_confirmation table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assistance_confirmation ADD check_in_time DATETIME DEFAULT NULL, ADD check_out_time DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assistance_confirmation DROP check_in_time, DROP check_out_time');
    }
}

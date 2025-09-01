<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250901070900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add check-in and check-out times to assistance confirmation';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assistance_confirmation ADD check_in_time TIME DEFAULT NULL, ADD check_out_time TIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assistance_confirmation DROP check_in_time, DROP check_out_time');
    }
}

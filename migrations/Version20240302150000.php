<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds alias, network_id, and phone_number to material_unit';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material_unit ADD COLUMN alias VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE material_unit ADD COLUMN network_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE material_unit ADD COLUMN phone_number VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // SQLite doesn't support DROP COLUMN in older versions easily, but for standard SQL:
        // $this->addSql('ALTER TABLE material_unit DROP COLUMN alias');
        // $this->addSql('ALTER TABLE material_unit DROP COLUMN network_id');
        // $this->addSql('ALTER TABLE material_unit DROP COLUMN phone_number');
    }
}

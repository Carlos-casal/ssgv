<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Sync MaterialMovement entity with database by adding material_unit_id column.
 */
final class Version20260409120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add material_unit_id to material_movement table for better traceability.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE material_movement ADD material_unit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE material_movement ADD CONSTRAINT FK_MATERIAL_MOVEMENT_UNIT FOREIGN KEY (material_unit_id) REFERENCES material_unit (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_MATERIAL_MOVEMENT_UNIT ON material_movement (material_unit_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE material_movement DROP FOREIGN KEY FK_MATERIAL_MOVEMENT_UNIT');
        $this->addSql('DROP INDEX IDX_MATERIAL_MOVEMENT_UNIT ON material_movement');
        $this->addSql('ALTER TABLE material_movement DROP material_unit_id');
    }
}

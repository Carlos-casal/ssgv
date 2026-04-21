<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add unique constraint to material_stock to prevent duplicate entries for same material, location and batch.
 */
final class Version20260420000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique constraint to material_stock to prevent duplicate entries for same material, location and batch.';
    }

    public function up(Schema $schema): void
    {
        // Consolidate any existing duplicates before adding the index
        // We sum quantities of duplicates into one record and delete the others.
        $this->addSql("
            CREATE TEMPORARY TABLE stock_duplicates AS
            SELECT MIN(id) as keep_id, material_id, location_id, batch_id, SUM(quantity) as total_quantity
            FROM material_stock
            GROUP BY material_id, location_id, batch_id
            HAVING COUNT(*) > 1
        ");

        $this->addSql("
            UPDATE material_stock ms
            JOIN stock_duplicates sd ON ms.id = sd.keep_id
            SET ms.quantity = sd.total_quantity
        ");

        $this->addSql("
            DELETE ms FROM material_stock ms
            JOIN (
                SELECT ms2.id
                FROM material_stock ms2
                JOIN stock_duplicates sd ON ms2.material_id = sd.material_id
                    AND ms2.location_id <=> sd.location_id
                    AND ms2.batch_id <=> sd.batch_id
                WHERE ms2.id != sd.keep_id
            ) to_delete ON ms.id = to_delete.id
        ");

        $this->addSql("DROP TEMPORARY TABLE stock_duplicates");

        $this->addSql('CREATE UNIQUE INDEX UNIQ_MATERIAL_STOCK_ML_BATCH ON material_stock (material_id, location_id, batch_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_MATERIAL_STOCK_ML_BATCH ON material_stock');
    }
}

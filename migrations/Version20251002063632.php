<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251002063632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create FuelType entity and relate it to Vehicle';
    }

    public function up(Schema $schema): void
    {
        // Create the new fuel_type table
        $this->addSql('CREATE TABLE fuel_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4A37B085E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Add the new foreign key column to vehicle
        $this->addSql('ALTER TABLE vehicle ADD fuel_type_id INT DEFAULT NULL');

        // Populate the new fuel_type table with existing distinct values from vehicle.fuel_type
        // This is a good practice to avoid losing data, although in this dev case it might be empty
        $this->addSql('INSERT INTO fuel_type (name) SELECT DISTINCT fuel_type FROM vehicle WHERE fuel_type IS NOT NULL');

        // Update the new fuel_type_id column with the correct ids from the new fuel_type table
        $this->addSql('UPDATE vehicle v SET v.fuel_type_id = (SELECT ft.id FROM fuel_type ft WHERE ft.name = v.fuel_type)');

        // Drop the old column
        $this->addSql('ALTER TABLE vehicle DROP fuel_type');

        // Create the foreign key constraint
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4861A7389E5 FOREIGN KEY (fuel_type_id) REFERENCES fuel_type (id)');
        $this->addSql('CREATE INDEX IDX_1B80E4861A7389E5 ON vehicle (fuel_type_id)');
    }

    public function down(Schema $schema): void
    {
        // Drop the foreign key and the index
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4861A7389E5');
        $this->addSql('DROP INDEX IDX_1B80E4861A7389E5 ON vehicle');

        // Add the old column back
        $this->addSql('ALTER TABLE vehicle ADD fuel_type VARCHAR(255) DEFAULT NULL');

        // Restore data from fuel_type table back to the old column
        $this->addSql('UPDATE vehicle v SET v.fuel_type = (SELECT ft.name FROM fuel_type ft WHERE ft.id = v.fuel_type_id)');

        // Drop the fuel_type_id column
        $this->addSql('ALTER TABLE vehicle DROP fuel_type_id');

        // Drop the fuel_type table
        $this->addSql('DROP TABLE fuel_type');
    }
}
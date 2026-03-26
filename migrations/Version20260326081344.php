<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260326081344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maestro_material CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
        $this->addSql('ALTER TABLE material_batch CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21\' NOT NULL');
        $this->addSql('ALTER TABLE material_movement DROP FOREIGN KEY FK_B205B155816C6140');
        $this->addSql('ALTER TABLE material_movement DROP FOREIGN KEY FK_B205B15556A273CC');
        $this->addSql('ALTER TABLE material_movement ADD CONSTRAINT FK_B205B155816C6140 FOREIGN KEY (destination_id) REFERENCES location (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE material_movement ADD CONSTRAINT FK_B205B15556A273CC FOREIGN KEY (origin_id) REFERENCES location (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maestro_material CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
        $this->addSql('ALTER TABLE material_batch CHANGE unit_price unit_price NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, CHANGE iva iva NUMERIC(5, 2) DEFAULT \'21.00\' NOT NULL');
        $this->addSql('ALTER TABLE material_movement DROP FOREIGN KEY FK_B205B15556A273CC');
        $this->addSql('ALTER TABLE material_movement DROP FOREIGN KEY FK_B205B155816C6140');
        $this->addSql('ALTER TABLE material_movement ADD CONSTRAINT FK_B205B15556A273CC FOREIGN KEY (origin_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE material_movement ADD CONSTRAINT FK_B205B155816C6140 FOREIGN KEY (destination_id) REFERENCES location (id)');
    }
}

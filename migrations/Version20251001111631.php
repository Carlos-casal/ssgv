<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251001111631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Vehicle table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, make VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, year INT NOT NULL, license_plate VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1B80E486A148A4F4 (license_plate), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE vehicle');
    }
}
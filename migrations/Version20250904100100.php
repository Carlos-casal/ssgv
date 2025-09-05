<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250904100100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add detailed fields to Service entity for resources, tasks, etc.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service ADD afluencia VARCHAR(255) DEFAULT NULL, ADD num_svb INT DEFAULT NULL, ADD num_sva INT DEFAULT NULL, ADD num_svae INT DEFAULT NULL, ADD num_medico INT DEFAULT NULL, ADD num_enfermero INT DEFAULT NULL, ADD has_field_hospital TINYINT(1) DEFAULT NULL, ADD tasks LONGTEXT DEFAULT NULL, ADD has_provisions TINYINT(1) DEFAULT NULL, DROP num_medical');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service ADD num_medical INT DEFAULT NULL, DROP afluencia, DROP num_svb, DROP num_sva, DROP num_svae, DROP num_medico, DROP num_enfermero, DROP has_field_hospital, DROP tasks, DROP has_provisions');
    }
}

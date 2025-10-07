<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251006094600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add numero_identificacion, indicativo, and habilitado_conducir to volunteer table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer ADD numero_identificacion VARCHAR(255) DEFAULT NULL, ADD indicativo VARCHAR(255) DEFAULT NULL, ADD habilitado_conducir BOOLEAN NOT NULL');
        $this->addSql('UPDATE volunteer SET habilitado_conducir = false');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5140DEDD65A23594 ON volunteer (indicativo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5140DEDD65A23594 ON volunteer');
        $this->addSql('ALTER TABLE volunteer DROP numero_identificacion, DROP indicativo, DROP habilitado_conducir');
    }
}
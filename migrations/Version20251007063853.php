<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251007063853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE setting');
        $this->addSql('ALTER TABLE volunteer ADD numero_identificacion VARCHAR(255) DEFAULT NULL, ADD indicativo VARCHAR(255) DEFAULT NULL, ADD habilitado_conducir TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5140DEDB3D57FC59 ON volunteer (indicativo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE setting (id INT NOT NULL, setting_key VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, setting_value TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_setting_key (setting_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP INDEX UNIQ_5140DEDB3D57FC59 ON volunteer');
        $this->addSql('ALTER TABLE volunteer DROP numero_identificacion, DROP indicativo, DROP habilitado_conducir');
    }
}

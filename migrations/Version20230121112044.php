<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230121112044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Filesystem basic structure';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE filesystem (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE system ADD filesystem_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE configuration configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:json_document)\'');
        $this->addSql('ALTER TABLE system ADD CONSTRAINT FK_C94D118B4F05E558 FOREIGN KEY (filesystem_id) REFERENCES filesystem (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C94D118B4F05E558 ON system (filesystem_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE system DROP FOREIGN KEY FK_C94D118B4F05E558');
        $this->addSql('DROP TABLE filesystem');
        $this->addSql('DROP INDEX UNIQ_C94D118B4F05E558 ON system');
        $this->addSql('ALTER TABLE system DROP filesystem_id, CHANGE configuration configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}

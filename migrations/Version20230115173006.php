<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230115173006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add configuration on System';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE system ADD configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE system DROP configuration');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230507124620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add filetype to tempfile';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tempfile ADD filetype VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tempfile DROP filetype');
    }
}

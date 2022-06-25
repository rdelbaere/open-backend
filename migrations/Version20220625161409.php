<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220625161409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add default behavior for app';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app ADD is_default TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app DROP is_default');
    }
}

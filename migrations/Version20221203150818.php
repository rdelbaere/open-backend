<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221203150818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename isDefault to byDefault for App';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app CHANGE is_default by_default TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app CHANGE by_default is_default TINYINT(1) NOT NULL');
    }
}

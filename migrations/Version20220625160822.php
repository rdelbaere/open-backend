<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220625160822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add application data structure';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE app (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(40) NOT NULL, icon LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', runtime VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE app');
    }
}

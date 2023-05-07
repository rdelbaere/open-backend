<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230507123257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tempfile system';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tempfile (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id INT NOT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_74D53606A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tempfile ADD CONSTRAINT FK_74D53606A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tempfile DROP FOREIGN KEY FK_74D53606A76ED395');
        $this->addSql('DROP TABLE tempfile');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221203132925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'System and installed applications';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE system (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_C94D118BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE system_app (system_id INT NOT NULL, app_id INT NOT NULL, INDEX IDX_4D6DA14D0952FA5 (system_id), INDEX IDX_4D6DA147987212D (app_id), PRIMARY KEY(system_id, app_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE system ADD CONSTRAINT FK_C94D118BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE system_app ADD CONSTRAINT FK_4D6DA14D0952FA5 FOREIGN KEY (system_id) REFERENCES system (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE system_app ADD CONSTRAINT FK_4D6DA147987212D FOREIGN KEY (app_id) REFERENCES app (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE system_app');
        $this->addSql('DROP TABLE system');
    }
}

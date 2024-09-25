<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925123724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenements DROP FOREIGN KEY FK_E10AD400A76ED395');
        $this->addSql('DROP INDEX IDX_E10AD400A76ED395 ON evenements');
        $this->addSql('ALTER TABLE evenements CHANGE user_id admin_evenements_id INT DEFAULT NULL, CHANGE date annee DATE NOT NULL');
        $this->addSql('ALTER TABLE evenements ADD CONSTRAINT FK_E10AD400BD02F3B3 FOREIGN KEY (admin_evenements_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_E10AD400BD02F3B3 ON evenements (admin_evenements_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenements DROP FOREIGN KEY FK_E10AD400BD02F3B3');
        $this->addSql('DROP INDEX IDX_E10AD400BD02F3B3 ON evenements');
        $this->addSql('ALTER TABLE evenements CHANGE admin_evenements_id user_id INT DEFAULT NULL, CHANGE annee date DATE NOT NULL');
        $this->addSql('ALTER TABLE evenements ADD CONSTRAINT FK_E10AD400A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E10AD400A76ED395 ON evenements (user_id)');
    }
}

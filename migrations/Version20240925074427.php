<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925074427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D0929642B8210');
        $this->addSql('DROP INDEX IDX_AE8D0929642B8210 ON outsiders');
        $this->addSql('ALTER TABLE outsiders DROP admin_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outsiders ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D0929642B8210 FOREIGN KEY (admin_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AE8D0929642B8210 ON outsiders (admin_id)');
    }
}

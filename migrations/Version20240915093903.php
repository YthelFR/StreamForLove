<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240915093903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presentations DROP FOREIGN KEY FK_72936B1D52F21B7C');
        $this->addSql('DROP INDEX UNIQ_72936B1D52F21B7C ON presentations');
        $this->addSql('ALTER TABLE presentations DROP streamers_presentation_id');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E952F21B7C');
        $this->addSql('DROP INDEX UNIQ_1483A5E952F21B7C ON users');
        $this->addSql('ALTER TABLE users DROP streamers_presentation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presentations ADD streamers_presentation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE presentations ADD CONSTRAINT FK_72936B1D52F21B7C FOREIGN KEY (streamers_presentation_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_72936B1D52F21B7C ON presentations (streamers_presentation_id)');
        $this->addSql('ALTER TABLE users ADD streamers_presentation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E952F21B7C FOREIGN KEY (streamers_presentation_id) REFERENCES presentations (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E952F21B7C ON users (streamers_presentation_id)');
    }
}

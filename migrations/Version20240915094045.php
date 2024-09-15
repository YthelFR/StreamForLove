<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240915094045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD streamers_presentation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E952F21B7C FOREIGN KEY (streamers_presentation_id) REFERENCES presentations (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E952F21B7C ON users (streamers_presentation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E952F21B7C');
        $this->addSql('DROP INDEX UNIQ_1483A5E952F21B7C ON users');
        $this->addSql('ALTER TABLE users DROP streamers_presentation_id');
    }
}

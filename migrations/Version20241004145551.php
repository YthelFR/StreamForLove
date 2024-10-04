<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004145551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD cagnotte_streamers_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9EC765813 FOREIGN KEY (cagnotte_streamers_id) REFERENCES cagnotte (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9EC765813 ON users (cagnotte_streamers_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9EC765813');
        $this->addSql('DROP INDEX UNIQ_1483A5E9EC765813 ON users');
        $this->addSql('ALTER TABLE users DROP cagnotte_streamers_id');
    }
}

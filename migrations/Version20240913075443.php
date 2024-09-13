<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240913075443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles ADD blogueur_articles_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD316878A9D3E7 FOREIGN KEY (blogueur_articles_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_BFDD316878A9D3E7 ON articles (blogueur_articles_id)');
        $this->addSql('ALTER TABLE evenements ADD admin_evenements_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenements ADD CONSTRAINT FK_E10AD400BD02F3B3 FOREIGN KEY (admin_evenements_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_E10AD400BD02F3B3 ON evenements (admin_evenements_id)');
        $this->addSql('ALTER TABLE outsiders ADD admin_outsiders_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D09296A575EC8 FOREIGN KEY (admin_outsiders_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_AE8D09296A575EC8 ON outsiders (admin_outsiders_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_PSEUDO ON outsiders (pseudo)');
        $this->addSql('ALTER TABLE presentations ADD streamers_presentation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE presentations ADD CONSTRAINT FK_72936B1D52F21B7C FOREIGN KEY (streamers_presentation_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_72936B1D52F21B7C ON presentations (streamers_presentation_id)');
        $this->addSql('ALTER TABLE socials_network ADD users_socials_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE socials_network ADD CONSTRAINT FK_FE38835A95EF4C81 FOREIGN KEY (users_socials_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_FE38835A95EF4C81 ON socials_network (users_socials_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_PSEUDO ON users (pseudo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_PSEUDO ON users');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD316878A9D3E7');
        $this->addSql('DROP INDEX IDX_BFDD316878A9D3E7 ON articles');
        $this->addSql('ALTER TABLE articles DROP blogueur_articles_id');
        $this->addSql('ALTER TABLE evenements DROP FOREIGN KEY FK_E10AD400BD02F3B3');
        $this->addSql('DROP INDEX IDX_E10AD400BD02F3B3 ON evenements');
        $this->addSql('ALTER TABLE evenements DROP admin_evenements_id');
        $this->addSql('ALTER TABLE presentations DROP FOREIGN KEY FK_72936B1D52F21B7C');
        $this->addSql('DROP INDEX UNIQ_72936B1D52F21B7C ON presentations');
        $this->addSql('ALTER TABLE presentations DROP streamers_presentation_id');
        $this->addSql('ALTER TABLE socials_network DROP FOREIGN KEY FK_FE38835A95EF4C81');
        $this->addSql('DROP INDEX IDX_FE38835A95EF4C81 ON socials_network');
        $this->addSql('ALTER TABLE socials_network DROP users_socials_id');
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D09296A575EC8');
        $this->addSql('DROP INDEX IDX_AE8D09296A575EC8 ON outsiders');
        $this->addSql('DROP INDEX UNIQ_PSEUDO ON outsiders');
        $this->addSql('ALTER TABLE outsiders DROP admin_outsiders_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240915212221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D092967B3B43D');
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D09296A575EC8');
        $this->addSql('DROP INDEX IDX_AE8D092967B3B43D ON outsiders');
        $this->addSql('DROP INDEX IDX_AE8D09296A575EC8 ON outsiders');
        $this->addSql('ALTER TABLE outsiders ADD admin_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, DROP users_id, DROP admin_outsiders_id');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D0929642B8210 FOREIGN KEY (admin_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D0929A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_AE8D0929642B8210 ON outsiders (admin_id)');
        $this->addSql('CREATE INDEX IDX_AE8D0929A76ED395 ON outsiders (user_id)');
        $this->addSql('ALTER TABLE presentations ADD picture_path VARCHAR(255) DEFAULT NULL, DROP picture, CHANGE question1 question1 LONGTEXT DEFAULT NULL, CHANGE question2 question2 LONGTEXT DEFAULT NULL, CHANGE question3 question3 LONGTEXT DEFAULT NULL, CHANGE planning planning VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE socials_network DROP FOREIGN KEY FK_FE38835A67B3B43D');
        $this->addSql('ALTER TABLE socials_network DROP FOREIGN KEY FK_FE38835A95EF4C81');
        $this->addSql('DROP INDEX IDX_FE38835A67B3B43D ON socials_network');
        $this->addSql('DROP INDEX IDX_FE38835A95EF4C81 ON socials_network');
        $this->addSql('ALTER TABLE socials_network ADD user_id INT NOT NULL, DROP users_id, DROP users_socials_id');
        $this->addSql('ALTER TABLE socials_network ADD CONSTRAINT FK_FE38835AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_FE38835AA76ED395 ON socials_network (user_id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E952F21B7C');
        $this->addSql('DROP INDEX UNIQ_1483A5E952F21B7C ON users');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON users');
        $this->addSql('ALTER TABLE users ADD is_verified TINYINT(1) NOT NULL, DROP streamers_presentation_id, CHANGE password password VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presentations ADD picture VARCHAR(255) NOT NULL, DROP picture_path, CHANGE question1 question1 LONGTEXT NOT NULL, CHANGE question2 question2 LONGTEXT NOT NULL, CHANGE question3 question3 LONGTEXT NOT NULL, CHANGE planning planning VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE socials_network DROP FOREIGN KEY FK_FE38835AA76ED395');
        $this->addSql('DROP INDEX IDX_FE38835AA76ED395 ON socials_network');
        $this->addSql('ALTER TABLE socials_network ADD users_id INT DEFAULT NULL, ADD users_socials_id INT DEFAULT NULL, DROP user_id');
        $this->addSql('ALTER TABLE socials_network ADD CONSTRAINT FK_FE38835A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE socials_network ADD CONSTRAINT FK_FE38835A95EF4C81 FOREIGN KEY (users_socials_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FE38835A67B3B43D ON socials_network (users_id)');
        $this->addSql('CREATE INDEX IDX_FE38835A95EF4C81 ON socials_network (users_socials_id)');
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D0929642B8210');
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D0929A76ED395');
        $this->addSql('DROP INDEX IDX_AE8D0929642B8210 ON outsiders');
        $this->addSql('DROP INDEX IDX_AE8D0929A76ED395 ON outsiders');
        $this->addSql('ALTER TABLE outsiders ADD users_id INT DEFAULT NULL, ADD admin_outsiders_id INT DEFAULT NULL, DROP admin_id, DROP user_id');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D092967B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D09296A575EC8 FOREIGN KEY (admin_outsiders_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AE8D092967B3B43D ON outsiders (users_id)');
        $this->addSql('CREATE INDEX IDX_AE8D09296A575EC8 ON outsiders (admin_outsiders_id)');
        $this->addSql('ALTER TABLE users ADD streamers_presentation_id INT DEFAULT NULL, DROP is_verified, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E952F21B7C FOREIGN KEY (streamers_presentation_id) REFERENCES presentations (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E952F21B7C ON users (streamers_presentation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON users (email)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909092742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnes (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, entete VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, accroche LONGTEXT NOT NULL, texte LONGTEXT NOT NULL, INDEX IDX_BFDD316867B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenements (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, date DATE NOT NULL, donations INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_E10AD40067B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletters (id INT AUTO_INCREMENT NOT NULL, sujet VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, date_envoi DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE outsiders (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, pseudo VARCHAR(50) NOT NULL, twitch VARCHAR(255) NOT NULL, somme INT NOT NULL, INDEX IDX_AE8D092967B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presentations (id INT AUTO_INCREMENT NOT NULL, picture VARCHAR(255) NOT NULL, question1 LONGTEXT NOT NULL, question2 LONGTEXT NOT NULL, question3 LONGTEXT NOT NULL, clip1 VARCHAR(255) DEFAULT NULL, clip2 VARCHAR(255) DEFAULT NULL, clip3 VARCHAR(255) DEFAULT NULL, clip4 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE socials_network (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_FE38835A67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, streamers_presentation_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, is_valid TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_1483A5E952F21B7C (streamers_presentation_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD316867B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE evenements ADD CONSTRAINT FK_E10AD40067B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D092967B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE socials_network ADD CONSTRAINT FK_FE38835A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E952F21B7C FOREIGN KEY (streamers_presentation_id) REFERENCES presentations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD316867B3B43D');
        $this->addSql('ALTER TABLE evenements DROP FOREIGN KEY FK_E10AD40067B3B43D');
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D092967B3B43D');
        $this->addSql('ALTER TABLE socials_network DROP FOREIGN KEY FK_FE38835A67B3B43D');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E952F21B7C');
        $this->addSql('DROP TABLE abonnes');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE evenements');
        $this->addSql('DROP TABLE newsletters');
        $this->addSql('DROP TABLE outsiders');
        $this->addSql('DROP TABLE presentations');
        $this->addSql('DROP TABLE socials_network');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

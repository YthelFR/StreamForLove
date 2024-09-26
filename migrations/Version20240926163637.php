<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240926163637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenements_users (evenements_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_2553CA1F63C02CD4 (evenements_id), INDEX IDX_2553CA1F67B3B43D (users_id), PRIMARY KEY(evenements_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenements_users ADD CONSTRAINT FK_2553CA1F63C02CD4 FOREIGN KEY (evenements_id) REFERENCES evenements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenements_users ADD CONSTRAINT FK_2553CA1F67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenements DROP FOREIGN KEY FK_E10AD40067B3B43D');
        $this->addSql('ALTER TABLE evenements DROP FOREIGN KEY FK_E10AD400BD02F3B3');
        $this->addSql('DROP INDEX IDX_E10AD40067B3B43D ON evenements');
        $this->addSql('DROP INDEX IDX_E10AD400BD02F3B3 ON evenements');
        $this->addSql('ALTER TABLE evenements ADD thumbnail VARCHAR(255) DEFAULT NULL, DROP users_id, DROP admin_evenements_id, CHANGE date annee DATE NOT NULL');
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D0929642B8210');
        $this->addSql('DROP INDEX IDX_AE8D0929642B8210 ON outsiders');
        $this->addSql('ALTER TABLE outsiders DROP admin_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenements_users DROP FOREIGN KEY FK_2553CA1F63C02CD4');
        $this->addSql('ALTER TABLE evenements_users DROP FOREIGN KEY FK_2553CA1F67B3B43D');
        $this->addSql('DROP TABLE evenements_users');
        $this->addSql('ALTER TABLE evenements ADD users_id INT DEFAULT NULL, ADD admin_evenements_id INT DEFAULT NULL, DROP thumbnail, CHANGE annee date DATE NOT NULL');
        $this->addSql('ALTER TABLE evenements ADD CONSTRAINT FK_E10AD40067B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE evenements ADD CONSTRAINT FK_E10AD400BD02F3B3 FOREIGN KEY (admin_evenements_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E10AD40067B3B43D ON evenements (users_id)');
        $this->addSql('CREATE INDEX IDX_E10AD400BD02F3B3 ON evenements (admin_evenements_id)');
        $this->addSql('ALTER TABLE outsiders ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D0929642B8210 FOREIGN KEY (admin_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AE8D0929642B8210 ON outsiders (admin_id)');
    }
}

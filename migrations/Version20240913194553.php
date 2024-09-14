<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240913194553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE socials_network DROP FOREIGN KEY FK_FE38835A67B3B43D');
        $this->addSql('ALTER TABLE socials_network DROP FOREIGN KEY FK_FE38835A95EF4C81');
        $this->addSql('DROP INDEX IDX_FE38835A67B3B43D ON socials_network');
        $this->addSql('DROP INDEX IDX_FE38835A95EF4C81 ON socials_network');
        $this->addSql('ALTER TABLE socials_network ADD user_id INT DEFAULT NULL, DROP users_id, DROP users_socials_id');
        $this->addSql('ALTER TABLE socials_network ADD CONSTRAINT FK_FE38835AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_FE38835AA76ED395 ON socials_network (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE socials_network DROP FOREIGN KEY FK_FE38835AA76ED395');
        $this->addSql('DROP INDEX IDX_FE38835AA76ED395 ON socials_network');
        $this->addSql('ALTER TABLE socials_network ADD users_socials_id INT DEFAULT NULL, CHANGE user_id users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE socials_network ADD CONSTRAINT FK_FE38835A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE socials_network ADD CONSTRAINT FK_FE38835A95EF4C81 FOREIGN KEY (users_socials_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FE38835A67B3B43D ON socials_network (users_id)');
        $this->addSql('CREATE INDEX IDX_FE38835A95EF4C81 ON socials_network (users_socials_id)');
    }
}

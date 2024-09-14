<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240914175200 extends AbstractMigration
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
        $this->addSql('ALTER TABLE users DROP is_verified');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON users (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D0929642B8210');
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D0929A76ED395');
        $this->addSql('DROP INDEX IDX_AE8D0929642B8210 ON outsiders');
        $this->addSql('DROP INDEX IDX_AE8D0929A76ED395 ON outsiders');
        $this->addSql('ALTER TABLE outsiders ADD users_id INT DEFAULT NULL, ADD admin_outsiders_id INT DEFAULT NULL, DROP admin_id, DROP user_id');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D092967B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D09296A575EC8 FOREIGN KEY (admin_outsiders_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AE8D092967B3B43D ON outsiders (users_id)');
        $this->addSql('CREATE INDEX IDX_AE8D09296A575EC8 ON outsiders (admin_outsiders_id)');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON users');
        $this->addSql('ALTER TABLE users ADD is_verified TINYINT(1) NOT NULL');
    }
}

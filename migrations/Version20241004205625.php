<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004205625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cagnotte ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE cagnotte ADD CONSTRAINT FK_6342C752A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_6342C752A76ED395 ON cagnotte (user_id)');
        $this->addSql('ALTER TABLE evenements_users ADD CONSTRAINT FK_2553CA1F63C02CD4 FOREIGN KEY (evenements_id) REFERENCES evenements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenements_users ADD CONSTRAINT FK_2553CA1F67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE outsiders DROP FOREIGN KEY FK_AE8D0929642B8210');
        $this->addSql('DROP INDEX IDX_AE8D0929642B8210 ON outsiders');
        $this->addSql('ALTER TABLE outsiders DROP admin_id');
        $this->addSql('ALTER TABLE users ADD cagnotte_streamers_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9EC765813 FOREIGN KEY (cagnotte_streamers_id) REFERENCES cagnotte (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9EC765813 ON users (cagnotte_streamers_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cagnotte DROP FOREIGN KEY FK_6342C752A76ED395');
        $this->addSql('DROP INDEX IDX_6342C752A76ED395 ON cagnotte');
        $this->addSql('ALTER TABLE cagnotte DROP user_id');
        $this->addSql('ALTER TABLE evenements_users DROP FOREIGN KEY FK_2553CA1F63C02CD4');
        $this->addSql('ALTER TABLE evenements_users DROP FOREIGN KEY FK_2553CA1F67B3B43D');
        $this->addSql('ALTER TABLE outsiders ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE outsiders ADD CONSTRAINT FK_AE8D0929642B8210 FOREIGN KEY (admin_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AE8D0929642B8210 ON outsiders (admin_id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9EC765813');
        $this->addSql('DROP INDEX UNIQ_1483A5E9EC765813 ON users');
        $this->addSql('ALTER TABLE users DROP cagnotte_streamers_id');
    }
}

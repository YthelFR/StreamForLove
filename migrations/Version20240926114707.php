<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240926114707 extends AbstractMigration
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
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenements_users DROP FOREIGN KEY FK_2553CA1F63C02CD4');
        $this->addSql('ALTER TABLE evenements_users DROP FOREIGN KEY FK_2553CA1F67B3B43D');
        $this->addSql('DROP TABLE evenements_users');
    }
}

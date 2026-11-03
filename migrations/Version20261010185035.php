<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261010185035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date_event DATE NOT NULL, adresse VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, heure TIME NOT NULL, nbr_personnes INT NOT NULL, total DOUBLE PRECISION NOT NULL, date_creation DATE NOT NULL, statut VARCHAR(255) NOT NULL, id_user_id INT NOT NULL, id_menu_id INT NOT NULL, INDEX IDX_6EEAA67D79F37AE5 (id_user_id), INDEX IDX_6EEAA67D124A4062 (id_menu_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, nbr_personnes INT NOT NULL, image VARCHAR(255) NOT NULL, theme VARCHAR(255) NOT NULL, regime VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D124A4062 FOREIGN KEY (id_menu_id) REFERENCES menu (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D79F37AE5');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D124A4062');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE menu');
    }
}

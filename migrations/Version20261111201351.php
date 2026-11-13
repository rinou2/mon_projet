<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261111201351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD date_en_attente DATETIME NOT NULL, ADD date_acceptee DATETIME NOT NULL, ADD date_en_preparation DATETIME NOT NULL, ADD date_en_cours_de_livraison DATETIME NOT NULL, ADD date_livree DATETIME NOT NULL, ADD date_terminee DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP date_en_attente, DROP date_acceptee, DROP date_en_preparation, DROP date_en_cours_de_livraison, DROP date_livree, DROP date_terminee');
    }
}

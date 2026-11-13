<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261112000451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE date_acceptee date_acceptee DATETIME DEFAULT NULL, CHANGE date_en_preparation date_en_preparation DATETIME DEFAULT NULL, CHANGE date_en_cours_de_livraison date_en_cours_de_livraison DATETIME DEFAULT NULL, CHANGE date_livree date_livree DATETIME DEFAULT NULL, CHANGE date_terminee date_terminee DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE date_acceptee date_acceptee DATETIME NOT NULL, CHANGE date_en_preparation date_en_preparation DATETIME NOT NULL, CHANGE date_en_cours_de_livraison date_en_cours_de_livraison DATETIME NOT NULL, CHANGE date_livree date_livree DATETIME NOT NULL, CHANGE date_terminee date_terminee DATETIME NOT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230831183049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE date_commande date_commande DATETIME NOT NULL, CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE cp cp VARCHAR(50) NOT NULL, CHANGE ville ville VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE reset_token reset_token VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE reset_token reset_token VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE commande CHANGE date_commande date_commande DATETIME DEFAULT NULL, CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE prenom prenom VARCHAR(100) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE cp cp VARCHAR(50) DEFAULT NULL, CHANGE ville ville VARCHAR(100) DEFAULT NULL');
    }
}

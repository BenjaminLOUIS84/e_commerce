<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821084256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE user_id user_id INT DEFAULT NULL, CHANGE numero_commande numero_commande INT NOT NULL');
        $this->addSql('ALTER TABLE commande_livre CHANGE livre_id livre_id INT DEFAULT NULL, CHANGE commande_id commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE format_livre CHANGE format_id format_id INT DEFAULT NULL, CHANGE livre_id livre_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE user_id user_id INT NOT NULL, CHANGE numero_commande numero_commande INT DEFAULT NULL');
        $this->addSql('ALTER TABLE format_livre CHANGE format_id format_id INT NOT NULL, CHANGE livre_id livre_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande_livre CHANGE commande_id commande_id INT NOT NULL, CHANGE livre_id livre_id INT NOT NULL');
    }
}
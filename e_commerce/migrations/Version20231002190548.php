<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002190548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire_newsletters DROP FOREIGN KEY FK_63CFB11E6480E3FB');
        $this->addSql('ALTER TABLE commentaire_newsletters DROP FOREIGN KEY FK_63CFB11EBA9CD190');
        $this->addSql('DROP TABLE commentaire_newsletters');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire_newsletters (commentaire_id INT NOT NULL, newsletters_id INT NOT NULL, INDEX IDX_63CFB11E6480E3FB (newsletters_id), INDEX IDX_63CFB11EBA9CD190 (commentaire_id), PRIMARY KEY(commentaire_id, newsletters_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commentaire_newsletters ADD CONSTRAINT FK_63CFB11E6480E3FB FOREIGN KEY (newsletters_id) REFERENCES newsletters (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire_newsletters ADD CONSTRAINT FK_63CFB11EBA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}

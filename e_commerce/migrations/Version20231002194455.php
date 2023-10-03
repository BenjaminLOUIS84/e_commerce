<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002194455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletters_commentaire DROP FOREIGN KEY FK_1B6419326480E3FB');
        $this->addSql('ALTER TABLE newsletters_commentaire DROP FOREIGN KEY FK_1B641932BA9CD190');
        $this->addSql('DROP TABLE newsletters_commentaire');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE newsletters_commentaire (newsletters_id INT NOT NULL, commentaire_id INT NOT NULL, INDEX IDX_1B6419326480E3FB (newsletters_id), INDEX IDX_1B641932BA9CD190 (commentaire_id), PRIMARY KEY(newsletters_id, commentaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE newsletters_commentaire ADD CONSTRAINT FK_1B6419326480E3FB FOREIGN KEY (newsletters_id) REFERENCES newsletters (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletters_commentaire ADD CONSTRAINT FK_1B641932BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}

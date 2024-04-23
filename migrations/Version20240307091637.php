<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307091637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_commentary ADD user_id INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP user');
        $this->addSql('ALTER TABLE article_commentary ADD CONSTRAINT FK_2075C34BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2075C34BA76ED395 ON article_commentary (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_commentary DROP FOREIGN KEY FK_2075C34BA76ED395');
        $this->addSql('DROP INDEX IDX_2075C34BA76ED395 ON article_commentary');
        $this->addSql('ALTER TABLE article_commentary ADD user VARCHAR(255) NOT NULL, DROP user_id, DROP created_at, DROP updated_at');
    }
}

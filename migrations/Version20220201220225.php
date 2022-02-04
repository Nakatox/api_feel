<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201220225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feel (id INT AUTO_INCREMENT NOT NULL, mood_id INT NOT NULL, owner_id INT NOT NULL, description LONGTEXT NOT NULL, note VARCHAR(255) NOT NULL, INDEX IDX_2D9BCC99B889D33E (mood_id), INDEX IDX_2D9BCC997E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mood (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feel ADD CONSTRAINT FK_2D9BCC99B889D33E FOREIGN KEY (mood_id) REFERENCES mood (id)');
        $this->addSql('ALTER TABLE feel ADD CONSTRAINT FK_2D9BCC997E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feel DROP FOREIGN KEY FK_2D9BCC99B889D33E');
        $this->addSql('ALTER TABLE feel DROP FOREIGN KEY FK_2D9BCC997E3C61F9');
        $this->addSql('DROP TABLE feel');
        $this->addSql('DROP TABLE mood');
        $this->addSql('DROP TABLE user');
    }
}

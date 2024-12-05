<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240801104351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resultat (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, examen_id INT NOT NULL, note DOUBLE PRECISION NOT NULL, status VARCHAR(20) NOT NULL, INDEX IDX_E7DB5DE28D0EB82 (candidat_id), INDEX IDX_E7DB5DE25C8659A (examen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE28D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE25C8659A FOREIGN KEY (examen_id) REFERENCES examen (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE28D0EB82');
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE25C8659A');
        $this->addSql('DROP TABLE resultat');
    }
}

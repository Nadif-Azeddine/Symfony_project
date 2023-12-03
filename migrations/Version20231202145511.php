<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231202145511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reclamation (num_reclamation BIGINT AUTO_INCREMENT NOT NULL, utilisateur_id VARCHAR(50) NOT NULL, corp_reclamation LONGTEXT NOT NULL, date_reclamation DATE NOT NULL, INDEX IDX_CE606404FB88E14F (utilisateur_id), PRIMARY KEY(num_reclamation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (pseudo_nom VARCHAR(50) NOT NULL, nom VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, sexe VARCHAR(2) NOT NULL, password VARCHAR(255) NOT NULL, profession VARCHAR(40) DEFAULT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', date_naissance DATE DEFAULT NULL, PRIMARY KEY(pseudo_nom)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (pseudo_nom)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404FB88E14F');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE utilisateur');
    }
}

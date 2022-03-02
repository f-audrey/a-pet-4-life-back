<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220224150903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, sexe VARCHAR(50) NOT NULL, date_of_birth DATE DEFAULT NULL, description LONGTEXT NOT NULL, status VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE asso_species (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, species_id INT NOT NULL, INDEX IDX_85236B12A76ED395 (user_id), INDEX IDX_85236B12B2A1D860 (species_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, rating INT NOT NULL, content LONGTEXT DEFAULT NULL, published_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE species (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(25) NOT NULL, name VARCHAR(100) DEFAULT NULL, firstname VARCHAR(50) DEFAULT NULL, lastname VARCHAR(50) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, mail VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, adress VARCHAR(255) DEFAULT NULL, zipcode INT DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, department VARCHAR(180) NOT NULL, region VARCHAR(180) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, status TINYINT(1) NOT NULL, picture VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL, slug VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE asso_species ADD CONSTRAINT FK_85236B12A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE asso_species ADD CONSTRAINT FK_85236B12B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asso_species DROP FOREIGN KEY FK_85236B12B2A1D860');
        $this->addSql('ALTER TABLE asso_species DROP FOREIGN KEY FK_85236B12A76ED395');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE asso_species');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE user');
    }
}

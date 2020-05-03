<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200501163414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, pseudo VARCHAR(60) NOT NULL, need_rgpd DATETIME DEFAULT NULL, est_active TINYINT(1) DEFAULT NULL, type_utilisateur INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garage (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, adresse VARCHAR(100) NOT NULL, code_postal VARCHAR(10) NOT NULL, ville VARCHAR(100) NOT NULL, pays VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marque (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modele (id INT AUTO_INCREMENT NOT NULL, marque_id INT NOT NULL, nom VARCHAR(100) NOT NULL, INDEX IDX_100285584827B9B2 (marque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, prix DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, voiture_id INT DEFAULT NULL, src_photo VARCHAR(255) DEFAULT NULL, alt_photo VARCHAR(255) DEFAULT NULL, INDEX IDX_14B78418181A8BA (voiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendezvous (id INT AUTO_INCREMENT NOT NULL, garage_id INT NOT NULL, client_id INT NOT NULL, vendeur_id INT DEFAULT NULL, date_rdv DATETIME NOT NULL, est_venu TINYINT(1) DEFAULT NULL, INDEX IDX_C09A9BA8C4FFF555 (garage_id), INDEX IDX_C09A9BA819EB6921 (client_id), INDEX IDX_C09A9BA8858C065E (vendeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendeur (id INT NOT NULL, garage_id INT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, ddn DATE NOT NULL, INDEX IDX_7AF49996C4FFF555 (garage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voiture (id INT AUTO_INCREMENT NOT NULL, modele_id INT NOT NULL, garage_id INT DEFAULT NULL, immatriculation VARCHAR(255) DEFAULT NULL, date_fabrication DATE NOT NULL, kilometrage INT DEFAULT NULL, est_vendue DATETIME DEFAULT NULL, type_carrosserie VARCHAR(100) NOT NULL, nb_portes INT NOT NULL, prix DOUBLE PRECISION DEFAULT NULL, src_photo_principal VARCHAR(255) DEFAULT NULL, alt_photo_principal VARCHAR(255) DEFAULT NULL, etat VARCHAR(100) DEFAULT NULL, INDEX IDX_E9E2810FAC14B70A (modele_id), INDEX IDX_E9E2810FC4FFF555 (garage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voiture_option (id INT AUTO_INCREMENT NOT NULL, voiture_id INT NOT NULL, option_id INT NOT NULL, nombre INT NOT NULL, INDEX IDX_7972CAAA181A8BA (voiture_id), INDEX IDX_7972CAAAA7C41D6F (option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modele ADD CONSTRAINT FK_100285584827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA8C4FFF555 FOREIGN KEY (garage_id) REFERENCES garage (id)');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE rendezvous ADD CONSTRAINT FK_C09A9BA8858C065E FOREIGN KEY (vendeur_id) REFERENCES vendeur (id)');
        $this->addSql('ALTER TABLE vendeur ADD CONSTRAINT FK_7AF49996C4FFF555 FOREIGN KEY (garage_id) REFERENCES garage (id)');
        $this->addSql('ALTER TABLE vendeur ADD CONSTRAINT FK_7AF49996BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FAC14B70A FOREIGN KEY (modele_id) REFERENCES modele (id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FC4FFF555 FOREIGN KEY (garage_id) REFERENCES garage (id)');
        $this->addSql('ALTER TABLE voiture_option ADD CONSTRAINT FK_7972CAAA181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE voiture_option ADD CONSTRAINT FK_7972CAAAA7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455BF396750');
        $this->addSql('ALTER TABLE vendeur DROP FOREIGN KEY FK_7AF49996BF396750');
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA819EB6921');
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA8C4FFF555');
        $this->addSql('ALTER TABLE vendeur DROP FOREIGN KEY FK_7AF49996C4FFF555');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FC4FFF555');
        $this->addSql('ALTER TABLE modele DROP FOREIGN KEY FK_100285584827B9B2');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FAC14B70A');
        $this->addSql('ALTER TABLE voiture_option DROP FOREIGN KEY FK_7972CAAAA7C41D6F');
        $this->addSql('ALTER TABLE rendezvous DROP FOREIGN KEY FK_C09A9BA8858C065E');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418181A8BA');
        $this->addSql('ALTER TABLE voiture_option DROP FOREIGN KEY FK_7972CAAA181A8BA');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE garage');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE modele');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE rendezvous');
        $this->addSql('DROP TABLE vendeur');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('DROP TABLE voiture_option');
    }
}

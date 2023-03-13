<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313001742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE souscripteur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE souscripteur (id INT NOT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, type VARCHAR(100) NOT NULL, price INT NOT NULL, surface INT NOT NULL, douche INT NOT NULL, chambre INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE utilisateur DROP CONSTRAINT fk_1d1c63b39e5f45ab');
        $this->addSql('DROP INDEX uniq_1d1c63b39e5f45ab');
        $this->addSql('ALTER TABLE utilisateur DROP critere_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE souscripteur_id_seq CASCADE');
        $this->addSql('DROP TABLE souscripteur');
        $this->addSql('ALTER TABLE utilisateur ADD critere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT fk_1d1c63b39e5f45ab FOREIGN KEY (critere_id) REFERENCES critere (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_1d1c63b39e5f45ab ON utilisateur (critere_id)');
    }
}

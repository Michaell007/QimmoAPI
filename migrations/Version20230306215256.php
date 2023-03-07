<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306215256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE annonce_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_annonce_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE annonce (id INT NOT NULL, type_annonce_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, etiquette VARCHAR(155) NOT NULL, montant INT NOT NULL, lieu VARCHAR(100) NOT NULL, nb_lit INT DEFAULT NULL, nb_douche INT DEFAULT NULL, dimension INT DEFAULT NULL, is_for_owner_site BOOLEAN NOT NULL, link_url VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F65593E595067D0A ON annonce (type_annonce_id)');
        $this->addSql('CREATE TABLE image (id INT NOT NULL, annonce_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C53D045F8805AB2F ON image (annonce_id)');
        $this->addSql('CREATE TABLE type_annonce (id INT NOT NULL, libelle VARCHAR(150) NOT NULL, description VARCHAR(200) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E595067D0A FOREIGN KEY (type_annonce_id) REFERENCES type_annonce (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE annonce_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_annonce_id_seq CASCADE');
        $this->addSql('ALTER TABLE annonce DROP CONSTRAINT FK_F65593E595067D0A');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F8805AB2F');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE type_annonce');
    }
}

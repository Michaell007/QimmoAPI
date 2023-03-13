<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230312221806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE critere_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE critere (id INT NOT NULL, type VARCHAR(100) NOT NULL, price INT NOT NULL, surface INT NOT NULL, chambre INT NOT NULL, douche INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE utilisateur ADD critere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD nom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD prenom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B39E5F45AB FOREIGN KEY (critere_id) REFERENCES critere (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B39E5F45AB ON utilisateur (critere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE utilisateur DROP CONSTRAINT FK_1D1C63B39E5F45AB');
        $this->addSql('DROP SEQUENCE critere_id_seq CASCADE');
        $this->addSql('DROP TABLE critere');
        $this->addSql('DROP INDEX UNIQ_1D1C63B39E5F45AB');
        $this->addSql('ALTER TABLE utilisateur DROP critere_id');
        $this->addSql('ALTER TABLE utilisateur DROP email');
        $this->addSql('ALTER TABLE utilisateur DROP nom');
        $this->addSql('ALTER TABLE utilisateur DROP prenom');
    }
}

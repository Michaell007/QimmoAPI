<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316181712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce ADD annonceur_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN annonce.annonceur_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5C8764012 FOREIGN KEY (annonceur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F65593E5C8764012 ON annonce (annonceur_id)');
        $this->addSql('ALTER TABLE utilisateur ALTER is_active SET DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE utilisateur ALTER is_active DROP DEFAULT');
        $this->addSql('ALTER TABLE annonce DROP CONSTRAINT FK_F65593E5C8764012');
        $this->addSql('DROP INDEX IDX_F65593E5C8764012');
        $this->addSql('ALTER TABLE annonce DROP annonceur_id');
    }
}

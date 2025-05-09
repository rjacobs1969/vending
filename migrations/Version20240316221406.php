<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240316221406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('CREATE TABLE example_category (uuid BINARY(16) NOT NULL, name VARCHAR(180) NOT NULL, version INT DEFAULT 1 NOT NULL, PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4');
        //$this->addSql('CREATE TABLE example_item (uuid BINARY(16) NOT NULL, name VARCHAR(180) NOT NULL, version INT DEFAULT 1 NOT NULL, example_category_uuid BINARY(16) DEFAULT NULL, INDEX IDX_20AFEC8CFA78C28E (example_category_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4');
        //$this->addSql('CREATE TABLE user (uuid BINARY(16) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4');
        //$this->addSql('ALTER TABLE example_item ADD CONSTRAINT FK_20AFEC8CFA78C28E FOREIGN KEY (example_category_uuid) REFERENCES example_category (uuid) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE example_item DROP FOREIGN KEY FK_20AFEC8CFA78C28E');
        //$this->addSql('DROP TABLE example_category');
        //$this->addSql('DROP TABLE example_item');
        //$this->addSql('DROP TABLE user');
    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151106001738 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Obituary ADD legacyCemetery VARCHAR(255) DEFAULT NULL, ADD districtId INT NOT NULL, CHANGE cemeteryId cemeteryId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692C3279E952 FOREIGN KEY (districtId) REFERENCES District (id)');
        $this->addSql('CREATE INDEX IDX_8B3B692C3279E952 ON Obituary (districtId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692C3279E952');
        $this->addSql('DROP INDEX IDX_8B3B692C3279E952 ON Obituary');
        $this->addSql('ALTER TABLE Obituary DROP legacyCemetery, DROP districtId, CHANGE cemeteryId cemeteryId INT NOT NULL');
    }
}

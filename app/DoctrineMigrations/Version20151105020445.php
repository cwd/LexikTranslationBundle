<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151105020445 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Obituary ADD gender VARCHAR(1) DEFAULT \'u\' NOT NULL, ADD type INT DEFAULT 1 NOT NULL, ADD hide TINYINT(1) DEFAULT \'0\' NOT NULL, ADD allowCondolence TINYINT(1) DEFAULT \'1\' NOT NULL, ADD showOnlyBirthYear TINYINT(1) DEFAULT \'0\' NOT NULL, ADD customerId INT UNSIGNED DEFAULT NULL, ADD obituaryId INT DEFAULT NULL, ADD portraitId INT DEFAULT NULL, CHANGE morticianId morticianId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692CF17FD7A5 FOREIGN KEY (customerId) REFERENCES Customer (id)');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692C68BF794B FOREIGN KEY (obituaryId) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692CFC411A71 FOREIGN KEY (portraitId) REFERENCES Media (id)');
        $this->addSql('CREATE INDEX IDX_8B3B692CF17FD7A5 ON Obituary (customerId)');
        $this->addSql('CREATE INDEX IDX_8B3B692C68BF794B ON Obituary (obituaryId)');
        $this->addSql('CREATE INDEX IDX_8B3B692CFC411A71 ON Obituary (portraitId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692CF17FD7A5');
        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692C68BF794B');
        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692CFC411A71');
        $this->addSql('DROP INDEX IDX_8B3B692CF17FD7A5 ON Obituary');
        $this->addSql('DROP INDEX IDX_8B3B692C68BF794B ON Obituary');
        $this->addSql('DROP INDEX IDX_8B3B692CFC411A71 ON Obituary');
        $this->addSql('ALTER TABLE Obituary DROP gender, DROP type, DROP hide, DROP allowCondolence, DROP showOnlyBirthYear, DROP customerId, DROP obituaryId, DROP portraitId, CHANGE morticianId morticianId INT NOT NULL');
    }
}

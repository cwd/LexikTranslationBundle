<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151010233859 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE MorticianMedia (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT DEFAULT NULL, mediaId INT NOT NULL, morticianId INT NOT NULL, INDEX IDX_7DB9B63927D9F5AC (mediaId), INDEX IDX_7DB9B63988BF07A8 (morticianId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE MorticianMedia ADD CONSTRAINT FK_7DB9B63927D9F5AC FOREIGN KEY (mediaId) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE MorticianMedia ADD CONSTRAINT FK_7DB9B63988BF07A8 FOREIGN KEY (morticianId) REFERENCES Mortician (id)');
        $this->addSql('ALTER TABLE Address ADD lng NUMERIC(10, 6) DEFAULT NULL, ADD lat NUMERIC(10, 6) DEFAULT NULL');
        $this->addSql('ALTER TABLE Mortician ADD shortName VARCHAR(150) DEFAULT NULL, ADD email VARCHAR(150) DEFAULT NULL, ADD country VARCHAR(2) DEFAULT \'AT\' NOT NULL, ADD contactName VARCHAR(200) DEFAULT NULL, ADD registeredAt DATETIME DEFAULT NULL, ADD logoId INT DEFAULT NULL, CHANGE name name VARCHAR(250) NOT NULL');
        $this->addSql('ALTER TABLE Mortician ADD CONSTRAINT FK_70067FB5B889CB4 FOREIGN KEY (logoId) REFERENCES Media (id)');
        $this->addSql('CREATE INDEX IDX_70067FB5B889CB4 ON Mortician (logoId)');
        $this->addSql('ALTER TABLE CemeteryAddress DROP lng, DROP lat');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE MorticianMedia');
        $this->addSql('ALTER TABLE Address DROP lng, DROP lat');
        $this->addSql('ALTER TABLE CemeteryAddress ADD lng DOUBLE PRECISION DEFAULT NULL, ADD lat DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE Mortician DROP FOREIGN KEY FK_70067FB5B889CB4');
        $this->addSql('DROP INDEX IDX_70067FB5B889CB4 ON Mortician');
        $this->addSql('ALTER TABLE Mortician DROP shortName, DROP email, DROP country, DROP contactName, DROP registeredAt, DROP logoId, CHANGE name name VARCHAR(200) NOT NULL COLLATE utf8_unicode_ci');
    }
}

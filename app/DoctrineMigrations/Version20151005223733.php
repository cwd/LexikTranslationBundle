<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151005223733 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Region DROP FOREIGN KEY FK_8CEF440FBA2A6B4');
        $this->addSql('DROP TABLE Country');
        $this->addSql('ALTER TABLE Address ADD country VARCHAR(2) NOT NULL');
        $this->addSql('DROP INDEX IDX_8CEF440FBA2A6B4 ON Region');
        $this->addSql('ALTER TABLE Region ADD country VARCHAR(2) NOT NULL, DROP countryId');
        $this->addSql('ALTER TABLE Obituary ADD country VARCHAR(2) DEFAULT \'at\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL COLLATE utf8_unicode_ci, alpha2 VARCHAR(2) NOT NULL COLLATE utf8_unicode_ci, alpha3 VARCHAR(3) NOT NULL COLLATE utf8_unicode_ci, countryCode VARCHAR(3) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_9CCEF0FAA164B0CD (countryCode), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Address DROP country');
        $this->addSql('ALTER TABLE Obituary DROP country');
        $this->addSql('ALTER TABLE Region ADD countryId INT NOT NULL, DROP country');
        $this->addSql('ALTER TABLE Region ADD CONSTRAINT FK_8CEF440FBA2A6B4 FOREIGN KEY (countryId) REFERENCES Country (id)');
        $this->addSql('CREATE INDEX IDX_8CEF440FBA2A6B4 ON Region (countryId)');
    }
}

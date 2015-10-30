<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151027003148 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(250) NOT NULL, shortName VARCHAR(150) DEFAULT NULL, slug VARCHAR(250) NOT NULL, description LONGTEXT DEFAULT NULL, phone VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', fax VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', email VARCHAR(150) DEFAULT NULL, webpage VARCHAR(200) DEFAULT NULL, vat VARCHAR(30) DEFAULT NULL, commercialRegNumber VARCHAR(30) DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, origId INT DEFAULT NULL, crmId INT DEFAULT NULL, country VARCHAR(2) DEFAULT \'AT\' NOT NULL, contactName VARCHAR(200) DEFAULT NULL, registeredAt DATETIME DEFAULT NULL, state TINYINT(1) DEFAULT \'0\' NOT NULL, partnerVienna TINYINT(1) DEFAULT \'0\' NOT NULL, logoId INT DEFAULT NULL, avatarId INT DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_800230D3989D9B62 (slug), INDEX IDX_800230D35B889CB4 (logoId), INDEX IDX_800230D386AA7DF8 (avatarId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SupplierHasMedia (supplierId INT NOT NULL, mediaId INT NOT NULL, INDEX IDX_B0F0A905DF05A1D3 (supplierId), INDEX IDX_B0F0A90527D9F5AC (mediaId), PRIMARY KEY(supplierId, mediaId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Company ADD CONSTRAINT FK_800230D35B889CB4 FOREIGN KEY (logoId) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE Company ADD CONSTRAINT FK_800230D386AA7DF8 FOREIGN KEY (avatarId) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE SupplierHasMedia ADD CONSTRAINT FK_B0F0A905DF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE SupplierHasMedia ADD CONSTRAINT FK_B0F0A90527D9F5AC FOREIGN KEY (mediaId) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE Supplier DROP FOREIGN KEY FK_625C0E28D3564642');
        $this->addSql('ALTER TABLE Supplier DROP FOREIGN KEY FK_625C0E28E8DE7170');
        $this->addSql('DROP INDEX UNIQ_625C0E28989D9B62 ON Supplier');
        $this->addSql('DROP INDEX IDX_625C0E28D3564642 ON Supplier');
        $this->addSql('DROP INDEX IDX_625C0E28E8DE7170 ON Supplier');
        $this->addSql('SET foreign_key_checks = 0');
        $this->addSql('ALTER TABLE Supplier DROP phone, DROP fax, DROP webpage, DROP vat, DROP deletedAt, DROP crmId, DROP slug, DROP name, DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy, DROP origId, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE Supplier ADD CONSTRAINT FK_625C0E28BF396750 FOREIGN KEY (id) REFERENCES Company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Mortician DROP FOREIGN KEY FK_70067FB5B889CB4');
        $this->addSql('ALTER TABLE Mortician DROP FOREIGN KEY FK_70067FB86AA7DF8');
        $this->addSql('ALTER TABLE Mortician DROP FOREIGN KEY FK_70067FBD3564642');
        $this->addSql('ALTER TABLE Mortician DROP FOREIGN KEY FK_70067FBE8DE7170');
        $this->addSql('DROP INDEX UNIQ_70067FB989D9B62 ON Mortician');
        $this->addSql('DROP INDEX IDX_70067FBD3564642 ON Mortician');
        $this->addSql('DROP INDEX IDX_70067FBE8DE7170 ON Mortician');
        $this->addSql('DROP INDEX IDX_70067FB5B889CB4 ON Mortician');
        $this->addSql('DROP INDEX IDX_70067FB86AA7DF8 ON Mortician');
        $this->addSql('ALTER TABLE Mortician DROP name, DROP slug, DROP description, DROP phone, DROP fax, DROP webpage, DROP vat, DROP commercialRegNumber, DROP deletedAt, DROP origMorticianId, DROP crmId, DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy, DROP shortName, DROP email, DROP country, DROP contactName, DROP registeredAt, DROP logoId, DROP state, DROP avatarId, DROP partnerVienna, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE Mortician ADD CONSTRAINT FK_70067FBBF396750 FOREIGN KEY (id) REFERENCES Company (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Supplier DROP FOREIGN KEY FK_625C0E28BF396750');
        $this->addSql('ALTER TABLE Mortician DROP FOREIGN KEY FK_70067FBBF396750');
        $this->addSql('DROP TABLE Company');
        $this->addSql('DROP TABLE SupplierHasMedia');
        $this->addSql('ALTER TABLE Mortician ADD name VARCHAR(250) NOT NULL COLLATE utf8_unicode_ci, ADD slug VARCHAR(250) NOT NULL COLLATE utf8_unicode_ci, ADD description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD phone VARCHAR(35) DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:phone_number)\', ADD fax VARCHAR(35) DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:phone_number)\', ADD webpage VARCHAR(200) DEFAULT NULL COLLATE utf8_unicode_ci, ADD vat VARCHAR(30) DEFAULT NULL COLLATE utf8_unicode_ci, ADD commercialRegNumber VARCHAR(30) DEFAULT NULL COLLATE utf8_unicode_ci, ADD deletedAt DATETIME DEFAULT NULL, ADD origMorticianId INT DEFAULT NULL, ADD crmId INT DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL, ADD shortName VARCHAR(150) DEFAULT NULL COLLATE utf8_unicode_ci, ADD email VARCHAR(150) DEFAULT NULL COLLATE utf8_unicode_ci, ADD country VARCHAR(2) DEFAULT \'AT\' NOT NULL COLLATE utf8_unicode_ci, ADD contactName VARCHAR(200) DEFAULT NULL COLLATE utf8_unicode_ci, ADD registeredAt DATETIME DEFAULT NULL, ADD logoId INT DEFAULT NULL, ADD state TINYINT(1) DEFAULT \'0\' NOT NULL, ADD avatarId INT DEFAULT NULL, ADD partnerVienna TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE Mortician ADD CONSTRAINT FK_70067FB5B889CB4 FOREIGN KEY (logoId) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE Mortician ADD CONSTRAINT FK_70067FB86AA7DF8 FOREIGN KEY (avatarId) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE Mortician ADD CONSTRAINT FK_70067FBD3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Mortician ADD CONSTRAINT FK_70067FBE8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70067FB989D9B62 ON Mortician (slug)');
        $this->addSql('CREATE INDEX IDX_70067FBD3564642 ON Mortician (createdBy)');
        $this->addSql('CREATE INDEX IDX_70067FBE8DE7170 ON Mortician (updatedBy)');
        $this->addSql('CREATE INDEX IDX_70067FB5B889CB4 ON Mortician (logoId)');
        $this->addSql('CREATE INDEX IDX_70067FB86AA7DF8 ON Mortician (avatarId)');
        $this->addSql('ALTER TABLE Supplier ADD phone VARCHAR(35) DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:phone_number)\', ADD fax VARCHAR(35) DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:phone_number)\', ADD webpage VARCHAR(200) DEFAULT NULL COLLATE utf8_unicode_ci, ADD vat VARCHAR(30) DEFAULT NULL COLLATE utf8_unicode_ci, ADD deletedAt DATETIME DEFAULT NULL, ADD crmId INT DEFAULT NULL, ADD slug VARCHAR(250) NOT NULL COLLATE utf8_unicode_ci, ADD name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL, ADD origId INT DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE Supplier ADD CONSTRAINT FK_625C0E28D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Supplier ADD CONSTRAINT FK_625C0E28E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_625C0E28989D9B62 ON Supplier (slug)');
        $this->addSql('CREATE INDEX IDX_625C0E28D3564642 ON Supplier (createdBy)');
        $this->addSql('CREATE INDEX IDX_625C0E28E8DE7170 ON Supplier (updatedBy)');
    }
}

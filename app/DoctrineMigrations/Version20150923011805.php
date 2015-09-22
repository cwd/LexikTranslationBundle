<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150923011805 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Address (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(200) NOT NULL, street2 VARCHAR(200) DEFAULT NULL, zipcode INT NOT NULL, country VARCHAR(200) NOT NULL, regionId INT NOT NULL, discriminator VARCHAR(255) NOT NULL, INDEX IDX_C2F3561D9962506A (regionId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE CemetryAdministration (id INT NOT NULL, phone VARCHAR(75) DEFAULT NULL, fax VARCHAR(75) DEFAULT NULL, email VARCHAR(75) DEFAULT NULL, webpage VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE OrderItem (id INT AUTO_INCREMENT NOT NULL, amount INT DEFAULT 0 NOT NULL, price NUMERIC(5, 2) NOT NULL, orderId INT NOT NULL, productId INT NOT NULL, INDEX IDX_33E85E19FA237437 (orderId), INDEX IDX_33E85E1936799605 (productId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id INT UNSIGNED AUTO_INCREMENT NOT NULL, firstname VARCHAR(150) NOT NULL, lastname VARCHAR(150) NOT NULL, email VARCHAR(200) NOT NULL, password VARCHAR(64) NOT NULL, salt VARCHAR(20) NOT NULL, enabled TINYINT(1) DEFAULT \'1\' NOT NULL, deletedAt DATETIME DEFAULT NULL, passwordToken VARCHAR(100) DEFAULT NULL, passwordUpdatedAt DATETIME DEFAULT NULL, lastLoginAt DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE UserHasRole (userId INT UNSIGNED NOT NULL, roleId INT NOT NULL, INDEX IDX_5BB02BA164B64DCC (userId), INDEX IDX_5BB02BA1B8C2FD88 (roleId), PRIMARY KEY(userId, roleId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Product (id INT AUTO_INCREMENT NOT NULL, sellPrice NUMERIC(10, 0) DEFAULT \'0\' NOT NULL, basePrice NUMERIC(10, 0) DEFAULT NULL, mainImageId INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, supplierId INT DEFAULT NULL, mainImage_id INT DEFAULT NULL, INDEX IDX_1CF73D31DF05A1D3 (supplierId), INDEX IDX_1CF73D3144D00FAF (mainImage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MorticianUser (id INT UNSIGNED NOT NULL, morticianId INT NOT NULL, INDEX IDX_AF3AA0D788BF07A8 (morticianId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Cemetery (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(250) NOT NULL, slug VARCHAR(200) DEFAULT NULL, ownerName VARCHAR(200) DEFAULT NULL, administrationId INT DEFAULT NULL, UNIQUE INDEX UNIQ_60977170989D9B62 (slug), INDEX IDX_6097717098260155 (region_id), INDEX IDX_60977170D61738CB (administrationId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SupplierHasCemetery (cemeteryId INT NOT NULL, supplierId INT NOT NULL, INDEX IDX_D18C3C846DDD18E9 (cemeteryId), INDEX IDX_D18C3C84DF05A1D3 (supplierId), PRIMARY KEY(cemeteryId, supplierId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Customer (id INT UNSIGNED NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Condolence (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, public TINYINT(1) DEFAULT \'1\' NOT NULL, obituaryId INT NOT NULL, createdBy INT UNSIGNED NOT NULL, INDEX IDX_379C2BB468BF794B (obituaryId), INDEX IDX_379C2BB4D3564642 (createdBy), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Role (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(100) NOT NULL, name VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Supplier (id INT AUTO_INCREMENT NOT NULL, phone VARCHAR(30) DEFAULT NULL, fax VARCHAR(30) DEFAULT NULL, webpage VARCHAR(200) DEFAULT NULL, vat VARCHAR(30) DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, crmId INT DEFAULT NULL, parentSupplierId INT DEFAULT NULL, INDEX IDX_625C0E282C8F2CF0 (parentSupplierId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ObituaryHasSupplier (supplierId INT NOT NULL, obituaryId INT NOT NULL, INDEX IDX_F438FD70DF05A1D3 (supplierId), INDEX IDX_F438FD7068BF794B (obituaryId), PRIMARY KEY(supplierId, obituaryId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SupplierHasType (supplierId INT NOT NULL, supplierTypeId INT NOT NULL, INDEX IDX_306E8E42DF05A1D3 (supplierId), INDEX IDX_306E8E42CF7B7F10 (supplierTypeId), PRIMARY KEY(supplierId, supplierTypeId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE CustomerAddress (id INT NOT NULL, title VARCHAR(200) DEFAULT NULL, `default` TINYINT(1) DEFAULT \'0\', type VARCHAR(255) DEFAULT \'invoice\' NOT NULL, customerId INT UNSIGNED NOT NULL, INDEX IDX_2C05351F17FD7A5 (customerId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Mortician (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, slug VARCHAR(250) NOT NULL, description LONGTEXT DEFAULT NULL, phone INT DEFAULT NULL, fax INT DEFAULT NULL, webpage VARCHAR(200) DEFAULT NULL, vat VARCHAR(30) DEFAULT NULL, commercialRegNumber VARCHAR(30) DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, origMorticianId INT DEFAULT NULL, crmId INT DEFAULT NULL, parentMorticianId INT DEFAULT NULL, UNIQUE INDEX UNIQ_70067FB989D9B62 (slug), INDEX IDX_70067FB2A2BFC57 (parentMorticianId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MorticianHasCemetery (morticianId INT NOT NULL, cemeteryId INT NOT NULL, INDEX IDX_66AE813988BF07A8 (morticianId), INDEX IDX_66AE81396DDD18E9 (cemeteryId), PRIMARY KEY(morticianId, cemeteryId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MorticianHasSupplier (morticianId INT NOT NULL, supplierId INT NOT NULL, INDEX IDX_6465FE6188BF07A8 (morticianId), INDEX IDX_6465FE61DF05A1D3 (supplierId), PRIMARY KEY(morticianId, supplierId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SupplierUser (id INT UNSIGNED NOT NULL, supplierId INT NOT NULL, INDEX IDX_F70BC180DF05A1D3 (supplierId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Media (id INT AUTO_INCREMENT NOT NULL, mediatype VARCHAR(200) NOT NULL, filehash VARCHAR(64) NOT NULL, filename VARCHAR(250) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ProductHasMedia (Media_id INT NOT NULL, productId INT NOT NULL, INDEX IDX_7FB6834913E9BF23 (Media_id), INDEX IDX_7FB6834936799605 (productId), PRIMARY KEY(Media_id, productId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, slug VARCHAR(255) NOT NULL, countryId INT NOT NULL, UNIQUE INDEX UNIQ_8CEF440989D9B62 (slug), INDEX IDX_8CEF440FBA2A6B4 (countryId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SupplierAddress (id INT NOT NULL, supplierId INT DEFAULT NULL, UNIQUE INDEX UNIQ_68675DD4DF05A1D3 (supplierId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ObituaryEventType (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ProductCategory (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(150) NOT NULL, slug VARCHAR(200) NOT NULL, imageId INT DEFAULT NULL, parentCategoryId INT DEFAULT NULL, INDEX IDX_D26EBFC46FBD8652 (parentCategoryId), INDEX IDX_D26EBFC43DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE CemeteryAddress (id INT NOT NULL, lng DOUBLE PRECISION DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, cemeteryId INT NOT NULL, UNIQUE INDEX UNIQ_B6E554516DDD18E9 (cemeteryId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE CustomerOrder (id INT AUTO_INCREMENT NOT NULL, state VARCHAR(255) DEFAULT \'new\' NOT NULL, payedAt DATETIME DEFAULT NULL, deliveredAt DATETIME DEFAULT NULL, paymentReference VARCHAR(255) DEFAULT NULL, totalAmount NUMERIC(6, 2) DEFAULT NULL, customerId INT UNSIGNED NOT NULL, INDEX IDX_8E222305F17FD7A5 (customerId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ProductHasCategory (id INT AUTO_INCREMENT NOT NULL, sort INT DEFAULT 0 NOT NULL, productId INT NOT NULL, productCategoryId INT NOT NULL, INDEX IDX_C7756A8536799605 (productId), INDEX IDX_C7756A85F0561F31 (productCategoryId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE BasePrice (id INT AUTO_INCREMENT NOT NULL, price NUMERIC(10, 0) DEFAULT \'0\' NOT NULL, productId INT NOT NULL, supplierId INT NOT NULL, INDEX IDX_42BE319536799605 (productId), INDEX IDX_42BE3195DF05A1D3 (supplierId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Candle (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(150) DEFAULT NULL, expiresAt DATETIME NOT NULL, createdBy INT UNSIGNED NOT NULL, obituaryId INT NOT NULL, orderItemId INT NOT NULL, INDEX IDX_FCEBD52CD3564642 (createdBy), INDEX IDX_FCEBD52C68BF794B (obituaryId), INDEX IDX_FCEBD52CBBF22A26 (orderItemId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MorticianAddress (id INT NOT NULL, morticianId INT DEFAULT NULL, UNIQUE INDEX UNIQ_2CAC3F6588BF07A8 (morticianId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Obituary (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, titlePrefix VARCHAR(255) DEFAULT NULL, titlePostfix VARCHAR(255) DEFAULT NULL, bornAs VARCHAR(255) DEFAULT NULL, dayOfBirth DATE DEFAULT NULL, dayOfDeath DATE NOT NULL, slug VARCHAR(255) NOT NULL, deletedAt DATETIME DEFAULT NULL, cemeteryId INT NOT NULL, morticianId INT NOT NULL, UNIQUE INDEX UNIQ_8B3B692C989D9B62 (slug), INDEX IDX_8B3B692C6DDD18E9 (cemeteryId), INDEX IDX_8B3B692C88BF07A8 (morticianId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SupplierType (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ObituaryEvent (id INT AUTO_INCREMENT NOT NULL, dateStart DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, typeId INT NOT NULL, obituaryId INT NOT NULL, INDEX IDX_483914DE9BF49490 (typeId), INDEX IDX_483914DE68BF794B (obituaryId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Admin (id INT UNSIGNED NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Address ADD CONSTRAINT FK_C2F3561D9962506A FOREIGN KEY (regionId) REFERENCES Region (id)');
        $this->addSql('ALTER TABLE CemetryAdministration ADD CONSTRAINT FK_29B5372DBF396750 FOREIGN KEY (id) REFERENCES Address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE OrderItem ADD CONSTRAINT FK_33E85E19FA237437 FOREIGN KEY (orderId) REFERENCES CustomerOrder (id)');
        $this->addSql('ALTER TABLE OrderItem ADD CONSTRAINT FK_33E85E1936799605 FOREIGN KEY (productId) REFERENCES Product (id)');
        $this->addSql('ALTER TABLE UserHasRole ADD CONSTRAINT FK_5BB02BA164B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('ALTER TABLE UserHasRole ADD CONSTRAINT FK_5BB02BA1B8C2FD88 FOREIGN KEY (roleId) REFERENCES Role (id)');
        $this->addSql('ALTER TABLE Product ADD CONSTRAINT FK_1CF73D31DF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE Product ADD CONSTRAINT FK_1CF73D3144D00FAF FOREIGN KEY (mainImage_id) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE MorticianUser ADD CONSTRAINT FK_AF3AA0D788BF07A8 FOREIGN KEY (morticianId) REFERENCES Mortician (id)');
        $this->addSql('ALTER TABLE MorticianUser ADD CONSTRAINT FK_AF3AA0D7BF396750 FOREIGN KEY (id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Cemetery ADD CONSTRAINT FK_6097717098260155 FOREIGN KEY (region_id) REFERENCES Region (id)');
        $this->addSql('ALTER TABLE Cemetery ADD CONSTRAINT FK_60977170D61738CB FOREIGN KEY (administrationId) REFERENCES CemetryAdministration (id)');
        $this->addSql('ALTER TABLE SupplierHasCemetery ADD CONSTRAINT FK_D18C3C846DDD18E9 FOREIGN KEY (cemeteryId) REFERENCES Cemetery (id)');
        $this->addSql('ALTER TABLE SupplierHasCemetery ADD CONSTRAINT FK_D18C3C84DF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE Customer ADD CONSTRAINT FK_784FEC5FBF396750 FOREIGN KEY (id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Condolence ADD CONSTRAINT FK_379C2BB468BF794B FOREIGN KEY (obituaryId) REFERENCES Obituary (id)');
        $this->addSql('ALTER TABLE Condolence ADD CONSTRAINT FK_379C2BB4D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Supplier ADD CONSTRAINT FK_625C0E282C8F2CF0 FOREIGN KEY (parentSupplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE ObituaryHasSupplier ADD CONSTRAINT FK_F438FD70DF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE ObituaryHasSupplier ADD CONSTRAINT FK_F438FD7068BF794B FOREIGN KEY (obituaryId) REFERENCES Obituary (id)');
        $this->addSql('ALTER TABLE SupplierHasType ADD CONSTRAINT FK_306E8E42DF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE SupplierHasType ADD CONSTRAINT FK_306E8E42CF7B7F10 FOREIGN KEY (supplierTypeId) REFERENCES SupplierType (id)');
        $this->addSql('ALTER TABLE CustomerAddress ADD CONSTRAINT FK_2C05351F17FD7A5 FOREIGN KEY (customerId) REFERENCES Customer (id)');
        $this->addSql('ALTER TABLE CustomerAddress ADD CONSTRAINT FK_2C05351BF396750 FOREIGN KEY (id) REFERENCES Address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Mortician ADD CONSTRAINT FK_70067FB2A2BFC57 FOREIGN KEY (parentMorticianId) REFERENCES Mortician (id)');
        $this->addSql('ALTER TABLE MorticianHasCemetery ADD CONSTRAINT FK_66AE813988BF07A8 FOREIGN KEY (morticianId) REFERENCES Mortician (id)');
        $this->addSql('ALTER TABLE MorticianHasCemetery ADD CONSTRAINT FK_66AE81396DDD18E9 FOREIGN KEY (cemeteryId) REFERENCES Cemetery (id)');
        $this->addSql('ALTER TABLE MorticianHasSupplier ADD CONSTRAINT FK_6465FE6188BF07A8 FOREIGN KEY (morticianId) REFERENCES Mortician (id)');
        $this->addSql('ALTER TABLE MorticianHasSupplier ADD CONSTRAINT FK_6465FE61DF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE SupplierUser ADD CONSTRAINT FK_F70BC180DF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE SupplierUser ADD CONSTRAINT FK_F70BC180BF396750 FOREIGN KEY (id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ProductHasMedia ADD CONSTRAINT FK_7FB6834913E9BF23 FOREIGN KEY (Media_id) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE ProductHasMedia ADD CONSTRAINT FK_7FB6834936799605 FOREIGN KEY (productId) REFERENCES Product (id)');
        $this->addSql('ALTER TABLE Region ADD CONSTRAINT FK_8CEF440FBA2A6B4 FOREIGN KEY (countryId) REFERENCES Country (id)');
        $this->addSql('ALTER TABLE SupplierAddress ADD CONSTRAINT FK_68675DD4DF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE SupplierAddress ADD CONSTRAINT FK_68675DD4BF396750 FOREIGN KEY (id) REFERENCES Address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ProductCategory ADD CONSTRAINT FK_D26EBFC46FBD8652 FOREIGN KEY (parentCategoryId) REFERENCES ProductCategory (id)');
        $this->addSql('ALTER TABLE ProductCategory ADD CONSTRAINT FK_D26EBFC43DA5256D FOREIGN KEY (image_id) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE CemeteryAddress ADD CONSTRAINT FK_B6E554516DDD18E9 FOREIGN KEY (cemeteryId) REFERENCES Cemetery (id)');
        $this->addSql('ALTER TABLE CemeteryAddress ADD CONSTRAINT FK_B6E55451BF396750 FOREIGN KEY (id) REFERENCES Address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE CustomerOrder ADD CONSTRAINT FK_8E222305F17FD7A5 FOREIGN KEY (customerId) REFERENCES Customer (id)');
        $this->addSql('ALTER TABLE ProductHasCategory ADD CONSTRAINT FK_C7756A8536799605 FOREIGN KEY (productId) REFERENCES Product (id)');
        $this->addSql('ALTER TABLE ProductHasCategory ADD CONSTRAINT FK_C7756A85F0561F31 FOREIGN KEY (productCategoryId) REFERENCES ProductCategory (id)');
        $this->addSql('ALTER TABLE BasePrice ADD CONSTRAINT FK_42BE319536799605 FOREIGN KEY (productId) REFERENCES Product (id)');
        $this->addSql('ALTER TABLE BasePrice ADD CONSTRAINT FK_42BE3195DF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52CD3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52C68BF794B FOREIGN KEY (obituaryId) REFERENCES Obituary (id)');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52CBBF22A26 FOREIGN KEY (orderItemId) REFERENCES OrderItem (id)');
        $this->addSql('ALTER TABLE MorticianAddress ADD CONSTRAINT FK_2CAC3F6588BF07A8 FOREIGN KEY (morticianId) REFERENCES Mortician (id)');
        $this->addSql('ALTER TABLE MorticianAddress ADD CONSTRAINT FK_2CAC3F65BF396750 FOREIGN KEY (id) REFERENCES Address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692C6DDD18E9 FOREIGN KEY (cemeteryId) REFERENCES Cemetery (id)');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692C88BF07A8 FOREIGN KEY (morticianId) REFERENCES Mortician (id)');
        $this->addSql('ALTER TABLE ObituaryEvent ADD CONSTRAINT FK_483914DE9BF49490 FOREIGN KEY (typeId) REFERENCES ObituaryEventType (id)');
        $this->addSql('ALTER TABLE ObituaryEvent ADD CONSTRAINT FK_483914DE68BF794B FOREIGN KEY (obituaryId) REFERENCES Obituary (id)');
        $this->addSql('ALTER TABLE Admin ADD CONSTRAINT FK_49CF2272BF396750 FOREIGN KEY (id) REFERENCES User (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE CemetryAdministration DROP FOREIGN KEY FK_29B5372DBF396750');
        $this->addSql('ALTER TABLE CustomerAddress DROP FOREIGN KEY FK_2C05351BF396750');
        $this->addSql('ALTER TABLE SupplierAddress DROP FOREIGN KEY FK_68675DD4BF396750');
        $this->addSql('ALTER TABLE CemeteryAddress DROP FOREIGN KEY FK_B6E55451BF396750');
        $this->addSql('ALTER TABLE MorticianAddress DROP FOREIGN KEY FK_2CAC3F65BF396750');
        $this->addSql('ALTER TABLE Cemetery DROP FOREIGN KEY FK_60977170D61738CB');
        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52CBBF22A26');
        $this->addSql('ALTER TABLE Region DROP FOREIGN KEY FK_8CEF440FBA2A6B4');
        $this->addSql('ALTER TABLE UserHasRole DROP FOREIGN KEY FK_5BB02BA164B64DCC');
        $this->addSql('ALTER TABLE MorticianUser DROP FOREIGN KEY FK_AF3AA0D7BF396750');
        $this->addSql('ALTER TABLE Customer DROP FOREIGN KEY FK_784FEC5FBF396750');
        $this->addSql('ALTER TABLE Condolence DROP FOREIGN KEY FK_379C2BB4D3564642');
        $this->addSql('ALTER TABLE SupplierUser DROP FOREIGN KEY FK_F70BC180BF396750');
        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52CD3564642');
        $this->addSql('ALTER TABLE Admin DROP FOREIGN KEY FK_49CF2272BF396750');
        $this->addSql('ALTER TABLE OrderItem DROP FOREIGN KEY FK_33E85E1936799605');
        $this->addSql('ALTER TABLE ProductHasMedia DROP FOREIGN KEY FK_7FB6834936799605');
        $this->addSql('ALTER TABLE ProductHasCategory DROP FOREIGN KEY FK_C7756A8536799605');
        $this->addSql('ALTER TABLE BasePrice DROP FOREIGN KEY FK_42BE319536799605');
        $this->addSql('ALTER TABLE SupplierHasCemetery DROP FOREIGN KEY FK_D18C3C846DDD18E9');
        $this->addSql('ALTER TABLE MorticianHasCemetery DROP FOREIGN KEY FK_66AE81396DDD18E9');
        $this->addSql('ALTER TABLE CemeteryAddress DROP FOREIGN KEY FK_B6E554516DDD18E9');
        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692C6DDD18E9');
        $this->addSql('ALTER TABLE CustomerAddress DROP FOREIGN KEY FK_2C05351F17FD7A5');
        $this->addSql('ALTER TABLE CustomerOrder DROP FOREIGN KEY FK_8E222305F17FD7A5');
        $this->addSql('ALTER TABLE UserHasRole DROP FOREIGN KEY FK_5BB02BA1B8C2FD88');
        $this->addSql('ALTER TABLE Product DROP FOREIGN KEY FK_1CF73D31DF05A1D3');
        $this->addSql('ALTER TABLE SupplierHasCemetery DROP FOREIGN KEY FK_D18C3C84DF05A1D3');
        $this->addSql('ALTER TABLE Supplier DROP FOREIGN KEY FK_625C0E282C8F2CF0');
        $this->addSql('ALTER TABLE ObituaryHasSupplier DROP FOREIGN KEY FK_F438FD70DF05A1D3');
        $this->addSql('ALTER TABLE SupplierHasType DROP FOREIGN KEY FK_306E8E42DF05A1D3');
        $this->addSql('ALTER TABLE MorticianHasSupplier DROP FOREIGN KEY FK_6465FE61DF05A1D3');
        $this->addSql('ALTER TABLE SupplierUser DROP FOREIGN KEY FK_F70BC180DF05A1D3');
        $this->addSql('ALTER TABLE SupplierAddress DROP FOREIGN KEY FK_68675DD4DF05A1D3');
        $this->addSql('ALTER TABLE BasePrice DROP FOREIGN KEY FK_42BE3195DF05A1D3');
        $this->addSql('ALTER TABLE MorticianUser DROP FOREIGN KEY FK_AF3AA0D788BF07A8');
        $this->addSql('ALTER TABLE Mortician DROP FOREIGN KEY FK_70067FB2A2BFC57');
        $this->addSql('ALTER TABLE MorticianHasCemetery DROP FOREIGN KEY FK_66AE813988BF07A8');
        $this->addSql('ALTER TABLE MorticianHasSupplier DROP FOREIGN KEY FK_6465FE6188BF07A8');
        $this->addSql('ALTER TABLE MorticianAddress DROP FOREIGN KEY FK_2CAC3F6588BF07A8');
        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692C88BF07A8');
        $this->addSql('ALTER TABLE Product DROP FOREIGN KEY FK_1CF73D3144D00FAF');
        $this->addSql('ALTER TABLE ProductHasMedia DROP FOREIGN KEY FK_7FB6834913E9BF23');
        $this->addSql('ALTER TABLE ProductCategory DROP FOREIGN KEY FK_D26EBFC43DA5256D');
        $this->addSql('ALTER TABLE Address DROP FOREIGN KEY FK_C2F3561D9962506A');
        $this->addSql('ALTER TABLE Cemetery DROP FOREIGN KEY FK_6097717098260155');
        $this->addSql('ALTER TABLE ObituaryEvent DROP FOREIGN KEY FK_483914DE9BF49490');
        $this->addSql('ALTER TABLE ProductCategory DROP FOREIGN KEY FK_D26EBFC46FBD8652');
        $this->addSql('ALTER TABLE ProductHasCategory DROP FOREIGN KEY FK_C7756A85F0561F31');
        $this->addSql('ALTER TABLE OrderItem DROP FOREIGN KEY FK_33E85E19FA237437');
        $this->addSql('ALTER TABLE Condolence DROP FOREIGN KEY FK_379C2BB468BF794B');
        $this->addSql('ALTER TABLE ObituaryHasSupplier DROP FOREIGN KEY FK_F438FD7068BF794B');
        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52C68BF794B');
        $this->addSql('ALTER TABLE ObituaryEvent DROP FOREIGN KEY FK_483914DE68BF794B');
        $this->addSql('ALTER TABLE SupplierHasType DROP FOREIGN KEY FK_306E8E42CF7B7F10');
        $this->addSql('DROP TABLE Address');
        $this->addSql('DROP TABLE CemetryAdministration');
        $this->addSql('DROP TABLE OrderItem');
        $this->addSql('DROP TABLE Country');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE UserHasRole');
        $this->addSql('DROP TABLE Product');
        $this->addSql('DROP TABLE MorticianUser');
        $this->addSql('DROP TABLE Cemetery');
        $this->addSql('DROP TABLE SupplierHasCemetery');
        $this->addSql('DROP TABLE Customer');
        $this->addSql('DROP TABLE Condolence');
        $this->addSql('DROP TABLE Role');
        $this->addSql('DROP TABLE Supplier');
        $this->addSql('DROP TABLE ObituaryHasSupplier');
        $this->addSql('DROP TABLE SupplierHasType');
        $this->addSql('DROP TABLE CustomerAddress');
        $this->addSql('DROP TABLE Mortician');
        $this->addSql('DROP TABLE MorticianHasCemetery');
        $this->addSql('DROP TABLE MorticianHasSupplier');
        $this->addSql('DROP TABLE SupplierUser');
        $this->addSql('DROP TABLE Media');
        $this->addSql('DROP TABLE ProductHasMedia');
        $this->addSql('DROP TABLE Region');
        $this->addSql('DROP TABLE SupplierAddress');
        $this->addSql('DROP TABLE ObituaryEventType');
        $this->addSql('DROP TABLE ProductCategory');
        $this->addSql('DROP TABLE CemeteryAddress');
        $this->addSql('DROP TABLE CustomerOrder');
        $this->addSql('DROP TABLE ProductHasCategory');
        $this->addSql('DROP TABLE BasePrice');
        $this->addSql('DROP TABLE Candle');
        $this->addSql('DROP TABLE MorticianAddress');
        $this->addSql('DROP TABLE Obituary');
        $this->addSql('DROP TABLE SupplierType');
        $this->addSql('DROP TABLE ObituaryEvent');
        $this->addSql('DROP TABLE Admin');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151008011105 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE OrderItem ADD deletedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE Product ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Product ADD CONSTRAINT FK_1CF73D31D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Product ADD CONSTRAINT FK_1CF73D31E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_1CF73D31D3564642 ON Product (createdBy)');
        $this->addSql('CREATE INDEX IDX_1CF73D31E8DE7170 ON Product (updatedBy)');
        $this->addSql('ALTER TABLE Cemetery ADD deletedAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Cemetery ADD CONSTRAINT FK_60977170D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Cemetery ADD CONSTRAINT FK_60977170E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_60977170D3564642 ON Cemetery (createdBy)');
        $this->addSql('CREATE INDEX IDX_60977170E8DE7170 ON Cemetery (updatedBy)');
        $this->addSql('ALTER TABLE Address ADD deletedAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Address ADD CONSTRAINT FK_C2F3561DD3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Address ADD CONSTRAINT FK_C2F3561DE8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_C2F3561DD3564642 ON Address (createdBy)');
        $this->addSql('CREATE INDEX IDX_C2F3561DE8DE7170 ON Address (updatedBy)');
        $this->addSql('ALTER TABLE Condolence ADD deletedAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL, CHANGE createdBy createdBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Condolence ADD CONSTRAINT FK_379C2BB4E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_379C2BB4E8DE7170 ON Condolence (updatedBy)');
        $this->addSql('ALTER TABLE Role ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Role ADD CONSTRAINT FK_F75B2554D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Role ADD CONSTRAINT FK_F75B2554E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_F75B2554D3564642 ON Role (createdBy)');
        $this->addSql('CREATE INDEX IDX_F75B2554E8DE7170 ON Role (updatedBy)');
        $this->addSql('ALTER TABLE Supplier ADD slug VARCHAR(255) NOT NULL, ADD name VARCHAR(255) NOT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Supplier ADD CONSTRAINT FK_625C0E28D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Supplier ADD CONSTRAINT FK_625C0E28E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_625C0E28D3564642 ON Supplier (createdBy)');
        $this->addSql('CREATE INDEX IDX_625C0E28E8DE7170 ON Supplier (updatedBy)');
        $this->addSql('ALTER TABLE Mortician ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Mortician ADD CONSTRAINT FK_70067FBD3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Mortician ADD CONSTRAINT FK_70067FBE8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_70067FBD3564642 ON Mortician (createdBy)');
        $this->addSql('CREATE INDEX IDX_70067FBE8DE7170 ON Mortician (updatedBy)');
        $this->addSql('ALTER TABLE ProductCategory DROP FOREIGN KEY FK_D26EBFC46FBD8652');
        $this->addSql('DROP INDEX IDX_D26EBFC46FBD8652 ON ProductCategory');
        $this->addSql('ALTER TABLE ProductCategory ADD deletedAt DATETIME DEFAULT NULL, ADD lft INT NOT NULL, ADD lvl INT NOT NULL, ADD rgt INT NOT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD parentId INT DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL, CHANGE parentcategoryid root INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ProductCategory ADD CONSTRAINT FK_D26EBFC410EE4CEE FOREIGN KEY (parentId) REFERENCES ProductCategory (id)');
        $this->addSql('ALTER TABLE ProductCategory ADD CONSTRAINT FK_D26EBFC4D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE ProductCategory ADD CONSTRAINT FK_D26EBFC4E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_D26EBFC410EE4CEE ON ProductCategory (parentId)');
        $this->addSql('CREATE INDEX IDX_D26EBFC4D3564642 ON ProductCategory (createdBy)');
        $this->addSql('CREATE INDEX IDX_D26EBFC4E8DE7170 ON ProductCategory (updatedBy)');
        $this->addSql('ALTER TABLE CustomerOrder ADD deletedAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE CustomerOrder ADD CONSTRAINT FK_8E222305D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE CustomerOrder ADD CONSTRAINT FK_8E222305E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_8E222305D3564642 ON CustomerOrder (createdBy)');
        $this->addSql('CREATE INDEX IDX_8E222305E8DE7170 ON CustomerOrder (updatedBy)');
        $this->addSql('ALTER TABLE ProductHasCategory ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE ProductHasCategory ADD CONSTRAINT FK_C7756A85D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE ProductHasCategory ADD CONSTRAINT FK_C7756A85E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_C7756A85D3564642 ON ProductHasCategory (createdBy)');
        $this->addSql('CREATE INDEX IDX_C7756A85E8DE7170 ON ProductHasCategory (updatedBy)');
        $this->addSql('ALTER TABLE BasePrice ADD deletedAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE BasePrice ADD CONSTRAINT FK_42BE3195D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE BasePrice ADD CONSTRAINT FK_42BE3195E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_42BE3195D3564642 ON BasePrice (createdBy)');
        $this->addSql('CREATE INDEX IDX_42BE3195E8DE7170 ON BasePrice (updatedBy)');
        $this->addSql('ALTER TABLE Candle ADD deletedAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL, CHANGE createdBy createdBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52CE8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_FCEBD52CE8DE7170 ON Candle (updatedBy)');
        $this->addSql('ALTER TABLE Obituary ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692CD3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692CE8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_8B3B692CD3564642 ON Obituary (createdBy)');
        $this->addSql('CREATE INDEX IDX_8B3B692CE8DE7170 ON Obituary (updatedBy)');
        $this->addSql('ALTER TABLE SupplierType ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE SupplierType ADD CONSTRAINT FK_F64640E0D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE SupplierType ADD CONSTRAINT FK_F64640E0E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_F64640E0D3564642 ON SupplierType (createdBy)');
        $this->addSql('CREATE INDEX IDX_F64640E0E8DE7170 ON SupplierType (updatedBy)');
        $this->addSql('ALTER TABLE ObituaryEvent ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE ObituaryEvent ADD CONSTRAINT FK_483914DED3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE ObituaryEvent ADD CONSTRAINT FK_483914DEE8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_483914DED3564642 ON ObituaryEvent (createdBy)');
        $this->addSql('CREATE INDEX IDX_483914DEE8DE7170 ON ObituaryEvent (updatedBy)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Address DROP FOREIGN KEY FK_C2F3561DD3564642');
        $this->addSql('ALTER TABLE Address DROP FOREIGN KEY FK_C2F3561DE8DE7170');
        $this->addSql('DROP INDEX IDX_C2F3561DD3564642 ON Address');
        $this->addSql('DROP INDEX IDX_C2F3561DE8DE7170 ON Address');
        $this->addSql('ALTER TABLE Address DROP deletedAt, DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE BasePrice DROP FOREIGN KEY FK_42BE3195D3564642');
        $this->addSql('ALTER TABLE BasePrice DROP FOREIGN KEY FK_42BE3195E8DE7170');
        $this->addSql('DROP INDEX IDX_42BE3195D3564642 ON BasePrice');
        $this->addSql('DROP INDEX IDX_42BE3195E8DE7170 ON BasePrice');
        $this->addSql('ALTER TABLE BasePrice DROP deletedAt, DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52CE8DE7170');
        $this->addSql('DROP INDEX IDX_FCEBD52CE8DE7170 ON Candle');
        $this->addSql('ALTER TABLE Candle DROP deletedAt, DROP updatedAt, DROP createdAt, DROP updatedBy, CHANGE createdBy createdBy INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE Cemetery DROP FOREIGN KEY FK_60977170D3564642');
        $this->addSql('ALTER TABLE Cemetery DROP FOREIGN KEY FK_60977170E8DE7170');
        $this->addSql('DROP INDEX IDX_60977170D3564642 ON Cemetery');
        $this->addSql('DROP INDEX IDX_60977170E8DE7170 ON Cemetery');
        $this->addSql('ALTER TABLE Cemetery DROP deletedAt, DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE Condolence DROP FOREIGN KEY FK_379C2BB4E8DE7170');
        $this->addSql('DROP INDEX IDX_379C2BB4E8DE7170 ON Condolence');
        $this->addSql('ALTER TABLE Condolence DROP deletedAt, DROP updatedAt, DROP createdAt, DROP updatedBy, CHANGE createdBy createdBy INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE CustomerOrder DROP FOREIGN KEY FK_8E222305D3564642');
        $this->addSql('ALTER TABLE CustomerOrder DROP FOREIGN KEY FK_8E222305E8DE7170');
        $this->addSql('DROP INDEX IDX_8E222305D3564642 ON CustomerOrder');
        $this->addSql('DROP INDEX IDX_8E222305E8DE7170 ON CustomerOrder');
        $this->addSql('ALTER TABLE CustomerOrder DROP deletedAt, DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE Mortician DROP FOREIGN KEY FK_70067FBD3564642');
        $this->addSql('ALTER TABLE Mortician DROP FOREIGN KEY FK_70067FBE8DE7170');
        $this->addSql('DROP INDEX IDX_70067FBD3564642 ON Mortician');
        $this->addSql('DROP INDEX IDX_70067FBE8DE7170 ON Mortician');
        $this->addSql('ALTER TABLE Mortician DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692CD3564642');
        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692CE8DE7170');
        $this->addSql('DROP INDEX IDX_8B3B692CD3564642 ON Obituary');
        $this->addSql('DROP INDEX IDX_8B3B692CE8DE7170 ON Obituary');
        $this->addSql('ALTER TABLE Obituary DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE ObituaryEvent DROP FOREIGN KEY FK_483914DED3564642');
        $this->addSql('ALTER TABLE ObituaryEvent DROP FOREIGN KEY FK_483914DEE8DE7170');
        $this->addSql('DROP INDEX IDX_483914DED3564642 ON ObituaryEvent');
        $this->addSql('DROP INDEX IDX_483914DEE8DE7170 ON ObituaryEvent');
        $this->addSql('ALTER TABLE ObituaryEvent DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE OrderItem DROP deletedAt');
        $this->addSql('ALTER TABLE Product DROP FOREIGN KEY FK_1CF73D31D3564642');
        $this->addSql('ALTER TABLE Product DROP FOREIGN KEY FK_1CF73D31E8DE7170');
        $this->addSql('DROP INDEX IDX_1CF73D31D3564642 ON Product');
        $this->addSql('DROP INDEX IDX_1CF73D31E8DE7170 ON Product');
        $this->addSql('ALTER TABLE Product DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE ProductCategory DROP FOREIGN KEY FK_D26EBFC410EE4CEE');
        $this->addSql('ALTER TABLE ProductCategory DROP FOREIGN KEY FK_D26EBFC4D3564642');
        $this->addSql('ALTER TABLE ProductCategory DROP FOREIGN KEY FK_D26EBFC4E8DE7170');
        $this->addSql('DROP INDEX IDX_D26EBFC410EE4CEE ON ProductCategory');
        $this->addSql('DROP INDEX IDX_D26EBFC4D3564642 ON ProductCategory');
        $this->addSql('DROP INDEX IDX_D26EBFC4E8DE7170 ON ProductCategory');
        $this->addSql('ALTER TABLE ProductCategory ADD parentCategoryId INT DEFAULT NULL, DROP deletedAt, DROP lft, DROP lvl, DROP rgt, DROP root, DROP updatedAt, DROP createdAt, DROP parentId, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE ProductCategory ADD CONSTRAINT FK_D26EBFC46FBD8652 FOREIGN KEY (parentCategoryId) REFERENCES ProductCategory (id)');
        $this->addSql('CREATE INDEX IDX_D26EBFC46FBD8652 ON ProductCategory (parentCategoryId)');
        $this->addSql('ALTER TABLE ProductHasCategory DROP FOREIGN KEY FK_C7756A85D3564642');
        $this->addSql('ALTER TABLE ProductHasCategory DROP FOREIGN KEY FK_C7756A85E8DE7170');
        $this->addSql('DROP INDEX IDX_C7756A85D3564642 ON ProductHasCategory');
        $this->addSql('DROP INDEX IDX_C7756A85E8DE7170 ON ProductHasCategory');
        $this->addSql('ALTER TABLE ProductHasCategory DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE Role DROP FOREIGN KEY FK_F75B2554D3564642');
        $this->addSql('ALTER TABLE Role DROP FOREIGN KEY FK_F75B2554E8DE7170');
        $this->addSql('DROP INDEX IDX_F75B2554D3564642 ON Role');
        $this->addSql('DROP INDEX IDX_F75B2554E8DE7170 ON Role');
        $this->addSql('ALTER TABLE Role DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE Supplier DROP FOREIGN KEY FK_625C0E28D3564642');
        $this->addSql('ALTER TABLE Supplier DROP FOREIGN KEY FK_625C0E28E8DE7170');
        $this->addSql('DROP INDEX IDX_625C0E28D3564642 ON Supplier');
        $this->addSql('DROP INDEX IDX_625C0E28E8DE7170 ON Supplier');
        $this->addSql('ALTER TABLE Supplier DROP slug, DROP name, DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
        $this->addSql('ALTER TABLE SupplierType DROP FOREIGN KEY FK_F64640E0D3564642');
        $this->addSql('ALTER TABLE SupplierType DROP FOREIGN KEY FK_F64640E0E8DE7170');
        $this->addSql('DROP INDEX IDX_F64640E0D3564642 ON SupplierType');
        $this->addSql('DROP INDEX IDX_F64640E0E8DE7170 ON SupplierType');
        $this->addSql('ALTER TABLE SupplierType DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
    }
}

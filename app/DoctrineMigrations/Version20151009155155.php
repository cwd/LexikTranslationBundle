<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151009155155 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE UserHasRole DROP FOREIGN KEY FK_5BB02BA1B8C2FD88');
        $this->addSql('DROP TABLE Role');
        $this->addSql('DROP TABLE UserHasRole');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977D3564642');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977E8DE7170');
        $this->addSql('ALTER TABLE User DROP password, DROP salt, DROP enabled, DROP passwordToken, DROP passwordUpdatedAt, DROP lastLoginAt, DROP locked');
        $this->addSql('DROP INDEX idx_2da17977d3564642 ON User');
        $this->addSql('CREATE INDEX IDX_8D93D649D3564642 ON User (createdBy)');
        $this->addSql('DROP INDEX idx_2da17977e8de7170 ON User');
        $this->addSql('CREATE INDEX IDX_8D93D649E8DE7170 ON User (updatedBy)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Media ADD updatedAt DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ABED8E08B390F5DB ON Media (filehash)');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977D3564642');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977E8DE7170');
        $this->addSql('DROP INDEX idx_8d93d649d3564642 ON User');
        $this->addSql('CREATE INDEX IDX_2DA17977D3564642 ON User (createdBy)');
        $this->addSql('DROP INDEX idx_8d93d649e8de7170 ON User');
        $this->addSql('CREATE INDEX IDX_2DA17977E8DE7170 ON User (updatedBy)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977D3564642');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977E8DE7170');
        $this->addSql('DROP INDEX idx_2da17977d3564642 ON User');
        $this->addSql('CREATE INDEX IDX_8D93D649D3564642 ON User (createdBy)');
        $this->addSql('DROP INDEX idx_2da17977e8de7170 ON User');
        $this->addSql('CREATE INDEX IDX_8D93D649E8DE7170 ON User (updatedBy)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE TABLE Role (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(150) NOT NULL COLLATE utf8_unicode_ci, updatedAt DATETIME DEFAULT NULL, createdAt DATETIME DEFAULT NULL, createdBy INT UNSIGNED DEFAULT NULL, updatedBy INT UNSIGNED DEFAULT NULL, INDEX IDX_F75B2554D3564642 (createdBy), INDEX IDX_F75B2554E8DE7170 (updatedBy), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE UserHasRole (userId INT UNSIGNED NOT NULL, roleId INT NOT NULL, INDEX IDX_5BB02BA164B64DCC (userId), INDEX IDX_5BB02BA1B8C2FD88 (roleId), PRIMARY KEY(userId, roleId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Role ADD CONSTRAINT FK_F75B2554D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Role ADD CONSTRAINT FK_F75B2554E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE UserHasRole ADD CONSTRAINT FK_5BB02BA164B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('ALTER TABLE UserHasRole ADD CONSTRAINT FK_5BB02BA1B8C2FD88 FOREIGN KEY (roleId) REFERENCES Role (id)');
        $this->addSql('DROP INDEX UNIQ_ABED8E08B390F5DB ON Media');
        $this->addSql('ALTER TABLE Media DROP updatedAt');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_8D93D649D3564642');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_8D93D649E8DE7170');
        $this->addSql('ALTER TABLE User ADD password VARCHAR(64) NOT NULL COLLATE utf8_unicode_ci, ADD salt VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, ADD enabled TINYINT(1) DEFAULT \'1\' NOT NULL, ADD passwordToken VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci, ADD passwordUpdatedAt DATETIME DEFAULT NULL, ADD lastLoginAt DATETIME DEFAULT NULL, ADD locked TINYINT(1) DEFAULT \'0\'');
        $this->addSql('DROP INDEX idx_8d93d649d3564642 ON User');
        $this->addSql('CREATE INDEX IDX_2DA17977D3564642 ON User (createdBy)');
        $this->addSql('DROP INDEX idx_8d93d649e8de7170 ON User');
        $this->addSql('CREATE INDEX IDX_2DA17977E8DE7170 ON User (updatedBy)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_8D93D649D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_8D93D649E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
    }
}

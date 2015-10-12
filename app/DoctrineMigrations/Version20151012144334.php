<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151012144334 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE UserHasRole DROP FOREIGN KEY FK_5BB02BA1B8C2FD88');
        $this->addSql('CREATE TABLE UserGroup (user_id INT UNSIGNED NOT NULL, group_id INT UNSIGNED NOT NULL, INDEX IDX_954D5B0A76ED395 (user_id), INDEX IDX_954D5B0FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE FosGroup (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', updatedAt DATETIME DEFAULT NULL, createdAt DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_10BBCBBC5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE UserGroup ADD CONSTRAINT FK_954D5B0A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE UserGroup ADD CONSTRAINT FK_954D5B0FE54D947 FOREIGN KEY (group_id) REFERENCES FosGroup (id)');
        $this->addSql('DROP TABLE Role');
        $this->addSql('DROP TABLE UserHasRole');
        $this->addSql('ALTER TABLE User ADD username VARCHAR(255) NOT NULL, ADD username_canonical VARCHAR(255) NOT NULL, ADD email_canonical VARCHAR(255) NOT NULL, ADD last_login DATETIME DEFAULT NULL, ADD expired TINYINT(1) NOT NULL, ADD expires_at DATETIME DEFAULT NULL, ADD confirmation_token VARCHAR(255) DEFAULT NULL, ADD password_requested_at DATETIME DEFAULT NULL, ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD credentials_expired TINYINT(1) NOT NULL, ADD credentials_expire_at DATETIME DEFAULT NULL, DROP passwordToken, DROP passwordUpdatedAt, DROP lastLoginAt, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE salt salt VARCHAR(255) NOT NULL, CHANGE enabled enabled TINYINT(1) NOT NULL, CHANGE locked locked TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA1797792FC23A8 ON User (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA17977A0D96FBF ON User (email_canonical)');
        $this->addSql('ALTER TABLE Media ADD updatedAt DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ABED8E08B390F5DB ON Media (filehash)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE UserGroup DROP FOREIGN KEY FK_954D5B0FE54D947');
        $this->addSql('CREATE TABLE Role (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(150) NOT NULL COLLATE utf8_unicode_ci, updatedAt DATETIME DEFAULT NULL, createdAt DATETIME DEFAULT NULL, createdBy INT UNSIGNED DEFAULT NULL, updatedBy INT UNSIGNED DEFAULT NULL, INDEX IDX_F75B2554D3564642 (createdBy), INDEX IDX_F75B2554E8DE7170 (updatedBy), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE UserHasRole (userId INT UNSIGNED NOT NULL, roleId INT NOT NULL, INDEX IDX_5BB02BA164B64DCC (userId), INDEX IDX_5BB02BA1B8C2FD88 (roleId), PRIMARY KEY(userId, roleId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Role ADD CONSTRAINT FK_F75B2554D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Role ADD CONSTRAINT FK_F75B2554E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE UserHasRole ADD CONSTRAINT FK_5BB02BA164B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('ALTER TABLE UserHasRole ADD CONSTRAINT FK_5BB02BA1B8C2FD88 FOREIGN KEY (roleId) REFERENCES Role (id)');
        $this->addSql('DROP TABLE UserGroup');
        $this->addSql('DROP TABLE FosGroup');
        $this->addSql('DROP INDEX UNIQ_ABED8E08B390F5DB ON Media');
        $this->addSql('ALTER TABLE Media DROP updatedAt');
        $this->addSql('DROP INDEX UNIQ_2DA1797792FC23A8 ON User');
        $this->addSql('DROP INDEX UNIQ_2DA17977A0D96FBF ON User');
        $this->addSql('ALTER TABLE User ADD passwordToken VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci, ADD passwordUpdatedAt DATETIME DEFAULT NULL, ADD lastLoginAt DATETIME DEFAULT NULL, DROP username, DROP username_canonical, DROP email_canonical, DROP last_login, DROP expired, DROP expires_at, DROP confirmation_token, DROP password_requested_at, DROP roles, DROP credentials_expired, DROP credentials_expire_at, CHANGE email email VARCHAR(200) NOT NULL COLLATE utf8_unicode_ci, CHANGE enabled enabled TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE salt salt VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, CHANGE password password VARCHAR(64) NOT NULL COLLATE utf8_unicode_ci, CHANGE locked locked TINYINT(1) DEFAULT \'0\'');
    }
}

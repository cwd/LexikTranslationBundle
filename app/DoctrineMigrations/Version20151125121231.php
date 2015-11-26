<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151125121231 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

	$this->addSql('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
	$this->addSql('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');
	$this->addSql("SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL'");
        $this->addSql('ALTER TABLE MorticianUser DROP FOREIGN KEY FK_AF3AA0D7BF396750');
        $this->addSql('ALTER TABLE MorticianUser ADD userId INT UNSIGNED DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE MorticianUser ADD CONSTRAINT FK_AF3AA0D764B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF3AA0D764B64DCC ON MorticianUser (userId)');
        $this->addSql('ALTER TABLE Customer DROP FOREIGN KEY FK_784FEC5FBF396750');
        $this->addSql('ALTER TABLE Customer ADD userId INT UNSIGNED DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE Customer ADD CONSTRAINT FK_784FEC5F64B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_784FEC5F64B64DCC ON Customer (userId)');
        $this->addSql('ALTER TABLE CustomerAddress CHANGE customerId customerId INT NOT NULL');
        $this->addSql('ALTER TABLE SupplierUser DROP FOREIGN KEY FK_F70BC180BF396750');
        $this->addSql('ALTER TABLE SupplierUser ADD userId INT UNSIGNED DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE SupplierUser ADD CONSTRAINT FK_F70BC18064B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F70BC18064B64DCC ON SupplierUser (userId)');
        $this->addSql('ALTER TABLE User CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE CustomerOrder CHANGE customerId customerId INT NOT NULL');
        $this->addSql('ALTER TABLE Obituary CHANGE customerId customerId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Admin DROP FOREIGN KEY FK_49CF2272BF396750');
        $this->addSql('ALTER TABLE Admin ADD userId INT UNSIGNED DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE Admin ADD CONSTRAINT FK_49CF227264B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_49CF227264B64DCC ON Admin (userId)');
	$this->addSql('SET SQL_MODE=@OLD_SQL_MODE');
	$this->addSql('SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS');
	$this->addSql('SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Admin DROP FOREIGN KEY FK_49CF227264B64DCC');
        $this->addSql('DROP INDEX UNIQ_49CF227264B64DCC ON Admin');
        $this->addSql('ALTER TABLE Admin DROP userId, CHANGE id id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE Admin ADD CONSTRAINT FK_49CF2272BF396750 FOREIGN KEY (id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Customer DROP FOREIGN KEY FK_784FEC5F64B64DCC');
        $this->addSql('DROP INDEX UNIQ_784FEC5F64B64DCC ON Customer');
        $this->addSql('ALTER TABLE Customer DROP userId, CHANGE id id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE Customer ADD CONSTRAINT FK_784FEC5FBF396750 FOREIGN KEY (id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE CustomerAddress CHANGE customerId customerId INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE CustomerOrder CHANGE customerId customerId INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE MorticianUser DROP FOREIGN KEY FK_AF3AA0D764B64DCC');
        $this->addSql('DROP INDEX UNIQ_AF3AA0D764B64DCC ON MorticianUser');
        $this->addSql('ALTER TABLE MorticianUser DROP userId, CHANGE id id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE MorticianUser ADD CONSTRAINT FK_AF3AA0D7BF396750 FOREIGN KEY (id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Obituary CHANGE customerId customerId INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE SupplierUser DROP FOREIGN KEY FK_F70BC18064B64DCC');
        $this->addSql('DROP INDEX UNIQ_F70BC18064B64DCC ON SupplierUser');
        $this->addSql('ALTER TABLE SupplierUser DROP userId, CHANGE id id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE SupplierUser ADD CONSTRAINT FK_F70BC180BF396750 FOREIGN KEY (id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE User CHANGE type type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}

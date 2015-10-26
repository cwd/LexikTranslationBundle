<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151026224601 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Supplier CHANGE phone phone VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', CHANGE fax fax VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', CHANGE slug slug VARCHAR(250) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_625C0E28989D9B62 ON Supplier (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_625C0E28989D9B62 ON Supplier');
        $this->addSql('ALTER TABLE Supplier CHANGE phone phone VARCHAR(30) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE fax fax VARCHAR(30) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}

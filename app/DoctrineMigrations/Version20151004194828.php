<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151004194828 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE CemetryAdministration RENAME CemeteryAdministration;');
        $this->addSql('ALTER TABLE CemeteryAdministration CHANGE phone phone VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', CHANGE fax fax VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE CemetryAdministration (id INT NOT NULL, phone VARCHAR(75) DEFAULT NULL COLLATE utf8_unicode_ci, fax VARCHAR(75) DEFAULT NULL COLLATE utf8_unicode_ci, email VARCHAR(75) DEFAULT NULL COLLATE utf8_unicode_ci, webpage VARCHAR(150) DEFAULT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE CemetryAdministration ADD CONSTRAINT FK_29B5372DBF396750 FOREIGN KEY (id) REFERENCES Address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE CemeteryAdministration CHANGE phone phone VARCHAR(75) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE fax fax VARCHAR(75) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}

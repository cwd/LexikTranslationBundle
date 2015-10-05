<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151005233058 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Cemetery CHANGE slug slug VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE CemeteryAddress ADD district VARCHAR(200) DEFAULT NULL');
        $this->addSql('ALTER TABLE Obituary CHANGE country country VARCHAR(2) DEFAULT \'AT\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Cemetery CHANGE slug slug VARCHAR(200) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE CemeteryAddress DROP district');
        $this->addSql('ALTER TABLE Obituary CHANGE country country VARCHAR(2) DEFAULT \'at\' NOT NULL COLLATE utf8_unicode_ci');
    }
}

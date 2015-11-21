<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151121010543 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE User CHANGE firstname firstname VARCHAR(150) DEFAULT NULL, CHANGE lastname lastname VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE Condolence CHANGE state state VARCHAR(100) DEFAULT \'inactive\' NOT NULL');
        $this->addSql('ALTER TABLE Candle CHANGE state state VARCHAR(255) DEFAULT \'inactive\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Candle CHANGE state state TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE Condolence CHANGE state state TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE User CHANGE firstname firstname VARCHAR(150) NOT NULL COLLATE utf8_unicode_ci, CHANGE lastname lastname VARCHAR(150) NOT NULL COLLATE utf8_unicode_ci');
    }
}

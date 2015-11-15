<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151113144131 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ProductAvailability (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(2) NOT NULL, dateFrom DATETIME DEFAULT NULL, dateTo DATETIME DEFAULT NULL, state TINYINT(1) DEFAULT \'1\' NOT NULL, deletedAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, createdAt DATETIME DEFAULT NULL, productId INT NOT NULL, INDEX IDX_F39023B236799605 (productId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ProductAvailability ADD CONSTRAINT FK_F39023B236799605 FOREIGN KEY (productId) REFERENCES Product (id)');
        $this->addSql('ALTER TABLE Product ADD state TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE ProductCategory ADD state TINYINT(1) DEFAULT \'1\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ProductAvailability');
        $this->addSql('ALTER TABLE Product DROP state');
        $this->addSql('ALTER TABLE ProductCategory DROP state');
    }
}

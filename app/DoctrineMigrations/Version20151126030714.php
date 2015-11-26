<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151126030714 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692C9395C3F3');
        $this->addSql('DROP INDEX IDX_8B3B692C9395C3F3 ON Obituary');
        $this->addSql('ALTER TABLE Obituary DROP customer_id, CHANGE customerId customerId INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692CF17FD7A5 FOREIGN KEY (customerId) REFERENCES Customer (id)');
        $this->addSql('CREATE INDEX IDX_8B3B692CF17FD7A5 ON Obituary (customerId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692CF17FD7A5');
        $this->addSql('DROP INDEX IDX_8B3B692CF17FD7A5 ON Obituary');
        $this->addSql('ALTER TABLE Obituary ADD customer_id INT UNSIGNED DEFAULT NULL, CHANGE customerId customerId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692C9395C3F3 FOREIGN KEY (customer_id) REFERENCES Customer (id)');
        $this->addSql('CREATE INDEX IDX_8B3B692C9395C3F3 ON Obituary (customer_id)');
    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151125192054 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Customer DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE Customer DROP userId, CHANGE id id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE Customer ADD CONSTRAINT FK_784FEC5FBF396750 FOREIGN KEY (id) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_784FEC5FBF396750 ON Customer (id)');
        $this->addSql('ALTER TABLE Customer ADD PRIMARY KEY (id)');
        $this->addSql('DROP INDEX IDX_2C05351F17FD7A5 ON CustomerAddress');
        $this->addSql('ALTER TABLE CustomerAddress ADD customer_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE CustomerAddress ADD CONSTRAINT FK_2C053519395C3F3 FOREIGN KEY (customer_id) REFERENCES Customer (id)');
        $this->addSql('CREATE INDEX IDX_2C053519395C3F3 ON CustomerAddress (customer_id)');
        $this->addSql('DROP INDEX IDX_8E222305F17FD7A5 ON CustomerOrder');
        $this->addSql('ALTER TABLE CustomerOrder ADD customer_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE CustomerOrder ADD CONSTRAINT FK_8E2223059395C3F3 FOREIGN KEY (customer_id) REFERENCES Customer (id)');
        $this->addSql('CREATE INDEX IDX_8E2223059395C3F3 ON CustomerOrder (customer_id)');
        $this->addSql('DROP INDEX IDX_8B3B692CF17FD7A5 ON Obituary');
        $this->addSql('ALTER TABLE Obituary ADD customer_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Obituary ADD CONSTRAINT FK_8B3B692C9395C3F3 FOREIGN KEY (customer_id) REFERENCES Customer (id)');
        $this->addSql('CREATE INDEX IDX_8B3B692C9395C3F3 ON Obituary (customer_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Customer DROP FOREIGN KEY FK_784FEC5FBF396750');
        $this->addSql('DROP INDEX UNIQ_784FEC5FBF396750 ON Customer');
        $this->addSql('ALTER TABLE Customer DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE Customer ADD userId INT UNSIGNED NOT NULL, CHANGE id id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Customer ADD PRIMARY KEY (userId)');
        $this->addSql('ALTER TABLE CustomerAddress DROP FOREIGN KEY FK_2C053519395C3F3');
        $this->addSql('DROP INDEX IDX_2C053519395C3F3 ON CustomerAddress');
        $this->addSql('ALTER TABLE CustomerAddress DROP customer_id');
        $this->addSql('CREATE INDEX IDX_2C05351F17FD7A5 ON CustomerAddress (customerId)');
        $this->addSql('ALTER TABLE CustomerOrder DROP FOREIGN KEY FK_8E2223059395C3F3');
        $this->addSql('DROP INDEX IDX_8E2223059395C3F3 ON CustomerOrder');
        $this->addSql('ALTER TABLE CustomerOrder DROP customer_id');
        $this->addSql('CREATE INDEX IDX_8E222305F17FD7A5 ON CustomerOrder (customerId)');
        $this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692C9395C3F3');
        $this->addSql('DROP INDEX IDX_8B3B692C9395C3F3 ON Obituary');
        $this->addSql('ALTER TABLE Obituary DROP customer_id');
        $this->addSql('CREATE INDEX IDX_8B3B692CF17FD7A5 ON Obituary (customerId)');
    }
}

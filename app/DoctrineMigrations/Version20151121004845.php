<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151121004845 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ReminderHistory ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE Product ADD virtual TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE CustomerOrder ADD obituaryId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE CustomerOrder ADD CONSTRAINT FK_8E22230568BF794B FOREIGN KEY (obituaryId) REFERENCES Obituary (id)');
        $this->addSql('CREATE INDEX IDX_8E22230568BF794B ON CustomerOrder (obituaryId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE CustomerOrder DROP FOREIGN KEY FK_8E22230568BF794B');
        $this->addSql('DROP INDEX IDX_8E22230568BF794B ON CustomerOrder');
        $this->addSql('ALTER TABLE CustomerOrder DROP obituaryId');
        $this->addSql('ALTER TABLE Product DROP virtual');
        $this->addSql('ALTER TABLE ReminderHistory DROP updatedAt, DROP createdAt');
    }
}

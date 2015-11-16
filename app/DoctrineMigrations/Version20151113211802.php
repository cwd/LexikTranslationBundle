<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151113211802 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Candle ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52CD3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52CE8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_FCEBD52CD3564642 ON Candle (createdBy)');
        $this->addSql('CREATE INDEX IDX_FCEBD52CE8DE7170 ON Candle (updatedBy)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52CD3564642');
        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52CE8DE7170');
        $this->addSql('DROP INDEX IDX_FCEBD52CD3564642 ON Candle');
        $this->addSql('DROP INDEX IDX_FCEBD52CE8DE7170 ON Candle');
        $this->addSql('ALTER TABLE Candle DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
    }
}

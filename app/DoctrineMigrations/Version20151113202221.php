<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151113202221 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52CD3564642');
        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52CE8DE7170');
        $this->addSql('DROP INDEX IDX_FCEBD52CD3564642 ON Candle');
        $this->addSql('DROP INDEX IDX_FCEBD52CE8DE7170 ON Candle');
        $this->addSql('ALTER TABLE Candle ADD productId INT DEFAULT NULL, DROP createdBy, DROP updatedAt, DROP createdAt, DROP updatedBy');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52C36799605 FOREIGN KEY (productId) REFERENCES Product (id)');
        $this->addSql('CREATE INDEX IDX_FCEBD52C36799605 ON Candle (productId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52C36799605');
        $this->addSql('DROP INDEX IDX_FCEBD52C36799605 ON Candle');
        $this->addSql('ALTER TABLE Candle ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL, DROP productId');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52CD3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52CE8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_FCEBD52CD3564642 ON Candle (createdBy)');
        $this->addSql('CREATE INDEX IDX_FCEBD52CE8DE7170 ON Candle (updatedBy)');
    }
}

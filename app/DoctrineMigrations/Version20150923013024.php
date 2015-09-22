<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150923013024 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE User ADD updatedAt DATETIME DEFAULT NULL, ADD createdAt DATETIME DEFAULT NULL, ADD createdBy INT UNSIGNED DEFAULT NULL, ADD updatedBy INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_2DA17977D3564642 ON User (createdBy)');
        $this->addSql('CREATE INDEX IDX_2DA17977E8DE7170 ON User (updatedBy)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977D3564642');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977E8DE7170');
        $this->addSql('DROP INDEX IDX_2DA17977D3564642 ON User');
        $this->addSql('DROP INDEX IDX_2DA17977E8DE7170 ON User');
        $this->addSql('ALTER TABLE User DROP updatedAt, DROP createdAt, DROP createdBy, DROP updatedBy');
    }
}

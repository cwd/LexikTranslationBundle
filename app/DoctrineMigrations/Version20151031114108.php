<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151031114108 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ProductCategory ADD supplierTypeId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ProductCategory ADD CONSTRAINT FK_D26EBFC4CF7B7F10 FOREIGN KEY (supplierTypeId) REFERENCES SupplierType (id)');
        $this->addSql('CREATE INDEX IDX_D26EBFC4CF7B7F10 ON ProductCategory (supplierTypeId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ProductCategory DROP FOREIGN KEY FK_D26EBFC4CF7B7F10');
        $this->addSql('DROP INDEX IDX_D26EBFC4CF7B7F10 ON ProductCategory');
        $this->addSql('ALTER TABLE ProductCategory DROP supplierTypeId');
    }
}

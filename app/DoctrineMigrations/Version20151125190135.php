<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151125190135 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE SupplierUser MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE SupplierUser DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE SupplierUser DROP id');
        $this->addSql('ALTER TABLE SupplierUser ADD PRIMARY KEY (userId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE SupplierUser DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE SupplierUser ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE SupplierUser ADD PRIMARY KEY (id)');
    }
}

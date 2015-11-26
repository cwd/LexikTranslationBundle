<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151125190203 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE SupplierUser DROP FOREIGN KEY FK_F70BC18064B64DCC');
        $this->addSql('DROP INDEX UNIQ_F70BC18064B64DCC ON SupplierUser');
        $this->addSql('ALTER TABLE SupplierUser DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE SupplierUser CHANGE userid id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE SupplierUser ADD CONSTRAINT FK_F70BC180BF396750 FOREIGN KEY (id) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F70BC180BF396750 ON SupplierUser (id)');
        $this->addSql('ALTER TABLE SupplierUser ADD PRIMARY KEY (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE SupplierUser DROP FOREIGN KEY FK_F70BC180BF396750');
        $this->addSql('DROP INDEX UNIQ_F70BC180BF396750 ON SupplierUser');
        $this->addSql('ALTER TABLE SupplierUser DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE SupplierUser CHANGE id userId INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE SupplierUser ADD CONSTRAINT FK_F70BC18064B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F70BC18064B64DCC ON SupplierUser (userId)');
        $this->addSql('ALTER TABLE SupplierUser ADD PRIMARY KEY (userId)');
    }
}

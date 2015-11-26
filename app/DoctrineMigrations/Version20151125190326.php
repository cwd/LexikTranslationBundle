<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151125190326 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
	$this->addSql('ALTER Table CustomerOrder DROP FOREIGN KEY FK_8E222305F17FD7A5');
	$this->addSql('ALTER TABLE CustomerAddress DROP FOREIGN KEY FK_2C05351F17FD7A5');
	$this->addSql('ALTER TABLE Obituary DROP FOREIGN KEY FK_8B3B692CF17FD7A5');
	$this->addSql('DROP INDEX UNIQ_784FEC5F64B64DCC ON Customer');
	$this->addSql('ALTER TABLE Customer DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE Customer MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE Customer CHANGE id id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Customer ADD PRIMARY KEY (userId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}

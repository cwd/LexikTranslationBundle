<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151125124019 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
	$this->addSql('UPDATE Admin set userId=id');
	$this->addSql('UPDATE Customer set userId=id');
	$this->addSql('UPDATE MorticianUser set userId=id');
	$this->addSql('UPDATE SupplierUser set userId=id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}

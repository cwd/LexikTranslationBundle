<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150925104352 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO Role VALUES(7, 'ROLE_MORTICIAN', 'Mortician')");
        $this->addSql("INSERT INTO Role VALUES(8, 'ROLE_SUPPLIER', 'Supplier')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM Role where id IN (7,8)');
    }
}

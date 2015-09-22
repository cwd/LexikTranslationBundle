<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150923013135 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO Role VALUES(1, 'ROLE_SUPER_ADMIN', 'Super Admin')");
        $this->addSql("INSERT INTO Role VALUES(2, 'ROLE_ADMIN', 'Admin')");
        $this->addSql("INSERT INTO Role VALUES(3, 'ROLE_BACKEND_ACCESS', 'Backend Access')");
        $this->addSql("INSERT INTO Role VALUES(4, 'ROLE_CUSTOMER', 'Customer')");
        $this->addSql("INSERT INTO Role VALUES(5, 'ROLE_USER', 'User')");
        $this->addSql("INSERT INTO Role VALUES(6, 'ROLE_SHOPMANAGER', 'Shopmanager')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM Role where id IN (1,2,3,4,5,6)');

    }
}

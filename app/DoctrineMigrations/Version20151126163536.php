<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151126163536 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $now = date('Y-m-d H:i:s');
        $this->addSql("INSERT INTO FosGroup VALUES(1, 'Super Admin', 'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}', '$now', '$now')");
        $this->addSql("INSERT INTO FosGroup VALUES(2, 'Admin', 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', '$now', '$now')");
        $this->addSql("INSERT INTO FosGroup VALUES(3, 'Backend Access', 'a:1:{i:0;s:19:\"ROLE_BACKEND_ACCESS\";}', '$now', '$now')");
        $this->addSql("INSERT INTO FosGroup VALUES(4, 'Customer', 'a:1:{i:0;s:13:\"ROLE_CUSTOMER\";}', '$now', '$now')");
        $this->addSql("INSERT INTO FosGroup VALUES(5, 'User', 'a:1:{i:0;s:9:\"ROLE_USER\";}', '$now', '$now')");
        $this->addSql("INSERT INTO FosGroup VALUES(6, 'Shopmanager', 'a:1:{i:0;s:16:\"ROLE_SHOPMANAGER\";}', '$now', '$now')");
        $this->addSql("INSERT INTO FosGroup VALUES(7, 'Mortician', 'a:1:{i:0;s:14:\"ROLE_MORTICIAN\";}', '$now', '$now')");
        $this->addSql("INSERT INTO FosGroup VALUES(8, 'Supplier', 'a:1:{i:0;s:13:\"ROLE_SUPPLIER\";}', '$now', '$now')");
        $this->addSql("INSERT INTO FosGroup VALUES(9, 'Translator', 'a:1:{i:0;s:15:\"ROLE_TRANSLATOR\";}', '$now', '$now')");

        $this->addSql("INSERT INTO Permission (name, title, entity) VALUES
          ('mortician.view', 'View mortician', 'mortician'),
          ('mortician.edit', 'Edit mortician', 'mortician'),
          ('mortician.user.view', 'View users', 'mortician'),
          ('mortician.user.create', 'Create users', 'mortician'),
          ('mortician.user.edit',   'Edit users', 'mortician'),
          ('mortician.user.delete', 'Delete users', 'mortician'),
          ('mortician.branch.view', 'View branch', 'mortician'),
          ('mortician.branch.create', 'Create branch', 'mortician'),
          ('mortician.branch.edit', 'Edit branch', 'mortician'),
          ('mortician.branch.delete', 'Delete branch', 'mortician'),
          ('mortician.supplier.view', 'View suppliers', 'mortician'),
          ('mortician.supplier.add', 'Add suppliers', 'mortician'),
          ('mortician.supplier.remove', 'Remove suppliers', 'mortician'),
          ('mortician.supplier.propose', 'Propose supplier', 'mortician')
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM FosGroup');
        $this->addSql('DELETE FROM Permission');
    }
}

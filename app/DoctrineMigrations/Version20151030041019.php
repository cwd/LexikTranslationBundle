<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151030041019 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $now = date('Y-m-d H:i:s');
        $this->addSql("INSERT INTO FosGroup VALUES(9, 'Translator', 'a:1:{i:0;s:15:\"ROLE_TRANSLATOR\";}', '$now', '$now')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM FosGroup where group_id IN (9)');

    }
}

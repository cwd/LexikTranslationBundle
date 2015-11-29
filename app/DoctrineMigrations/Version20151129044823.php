<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151129044823 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE lexik_trans_unit_translations SET file_id = 5 WHERE file_id = 1');
	$this->addSql('UPDATE lexik_trans_unit_translations SET file_id = 4 WHERE file_id = 3');

	$this->addSql('DELETE FROM lexik_translation_file WHERE id IN (1, 3)');

	$this->addSql("UPDATE lexik_translation_file SET path = 'Resources/translations' WHERE id = 2");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}

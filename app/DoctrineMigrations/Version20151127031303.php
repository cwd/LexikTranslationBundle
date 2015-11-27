<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151127031303 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO Permission (name, title, entity) VALUES
          ('mortician.cemetery.view', 'View Cemetery', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.cemetery.add', 'Add cemetery', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.cemetery.remove', 'Remove cemetery', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.media', 'Edit images', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.view',   'View obituary', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.create', 'Create users', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.edit', 'Edit obituary', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.delete', 'Delete obituary', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.candle.view', 'View candles', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.candle.create', 'Create candles', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.candle.edit', 'Edit candles', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.candle.delete', 'Delete candles', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.condolence.view', 'View condolences', 'Aspetos\Model\Entity\Mortician'),
	  ('mortician.obituary.condolence.create', 'Create condolences', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.condolence.edit', 'Edit condolences', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.obituary.condolence.delete', 'Delete condolences', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.order.view', 'View order', 'Aspetos\Model\Entity\Mortician'),
          ('mortician.order.edit', 'Edit order', 'Aspetos\Model\Entity\Mortician')
        ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}

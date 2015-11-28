<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151128013537 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX IDX_state ON Condolence (state)');
        $this->addSql('CREATE INDEX IDX_public ON Condolence (public)');
        $this->addSql('CREATE INDEX IDX_search ON Condolence (id, public, deletedAt, state)');
        $this->addSql('CREATE INDEX IDX_state ON Candle (state)');
        $this->addSql('CREATE INDEX IDX_search ON Candle (id, obituaryId, deletedAt, state)');
        $this->addSql('CREATE INDEX IDX_country ON Obituary (country)');
        $this->addSql('CREATE INDEX IDX_hide ON Obituary (hide)');
        $this->addSql('CREATE INDEX IDX_obituarysearch ON Obituary (id, deletedAt, type, hide, districtId, dayOfDeath, country)');
        $this->addSql('CREATE INDEX IDX_slug ON Obituary (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_state ON Candle');
        $this->addSql('DROP INDEX IDX_search ON Candle');
        $this->addSql('DROP INDEX IDX_state ON Condolence');
        $this->addSql('DROP INDEX IDX_public ON Condolence');
        $this->addSql('DROP INDEX IDX_search ON Condolence');
        $this->addSql('DROP INDEX IDX_country ON Obituary');
        $this->addSql('DROP INDEX IDX_hide ON Obituary');
        $this->addSql('DROP INDEX IDX_obituarysearch ON Obituary');
        $this->addSql('DROP INDEX IDX_slug ON Obituary');
    }
}

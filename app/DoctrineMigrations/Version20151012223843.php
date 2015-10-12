<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151012223843 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE District (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, slug VARCHAR(255) NOT NULL, regionId INT NOT NULL, INDEX IDX_C8B736D19962506A (regionId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE District ADD CONSTRAINT FK_C8B736D19962506A FOREIGN KEY (regionId) REFERENCES Region (id)');
        $this->addSql('ALTER TABLE Cemetery DROP FOREIGN KEY FK_6097717098260155');
        $this->addSql('DROP INDEX IDX_6097717098260155 ON Cemetery');
        $this->addSql('ALTER TABLE Cemetery DROP region_id');
        $this->addSql('ALTER TABLE Address ADD districtId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Address ADD CONSTRAINT FK_C2F3561D3279E952 FOREIGN KEY (districtId) REFERENCES District (id)');
        $this->addSql('CREATE INDEX IDX_C2F3561D3279E952 ON Address (districtId)');
        $this->addSql('ALTER TABLE CemeteryAddress DROP district');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Address DROP FOREIGN KEY FK_C2F3561D3279E952');
        $this->addSql('DROP TABLE District');
        $this->addSql('DROP INDEX IDX_C2F3561D3279E952 ON Address');
        $this->addSql('ALTER TABLE Address DROP districtId');
        $this->addSql('ALTER TABLE Cemetery ADD region_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Cemetery ADD CONSTRAINT FK_6097717098260155 FOREIGN KEY (region_id) REFERENCES Region (id)');
        $this->addSql('CREATE INDEX IDX_6097717098260155 ON Cemetery (region_id)');
        $this->addSql('ALTER TABLE CemeteryAddress ADD district VARCHAR(200) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}

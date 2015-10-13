<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151012174814 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Mortician ADD avatarId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Mortician ADD CONSTRAINT FK_70067FB86AA7DF8 FOREIGN KEY (avatarId) REFERENCES Media (id)');
        $this->addSql('CREATE INDEX IDX_70067FB86AA7DF8 ON Mortician (avatarId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Mortician DROP FOREIGN KEY FK_70067FB86AA7DF8');
        $this->addSql('DROP INDEX IDX_70067FB86AA7DF8 ON Mortician');
        $this->addSql('ALTER TABLE Mortician DROP avatarId');
    }
}

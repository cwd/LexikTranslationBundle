<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151125181931 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Admin DROP FOREIGN KEY FK_49CF227264B64DCC');
        $this->addSql('DROP INDEX UNIQ_49CF227264B64DCC ON Admin');
        $this->addSql('ALTER TABLE Admin DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE Admin CHANGE userid id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE Admin ADD CONSTRAINT FK_49CF2272BF396750 FOREIGN KEY (id) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_49CF2272BF396750 ON Admin (id)');
        $this->addSql('ALTER TABLE Admin ADD PRIMARY KEY (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Admin DROP FOREIGN KEY FK_49CF2272BF396750');
        $this->addSql('DROP INDEX UNIQ_49CF2272BF396750 ON Admin');
        $this->addSql('ALTER TABLE Admin DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE Admin CHANGE id userId INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE Admin ADD CONSTRAINT FK_49CF227264B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_49CF227264B64DCC ON Admin (userId)');
        $this->addSql('ALTER TABLE Admin ADD PRIMARY KEY (userId)');
    }
}

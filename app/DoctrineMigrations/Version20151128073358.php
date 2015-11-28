<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151128073358 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52C68BF794B');
        $this->addSql('DROP INDEX idx_state ON Candle');
        $this->addSql('CREATE INDEX IDX_statecolumn ON Candle (state)');
        $this->addSql('DROP INDEX idx_search ON Candle');
        $this->addSql('CREATE INDEX IDX_searchcolumn ON Candle (id, obituaryId, deletedAt, state)');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52C68BF794B FOREIGN KEY (obituaryId) REFERENCES Obituary (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Candle DROP FOREIGN KEY FK_FCEBD52C68BF794B');
        $this->addSql('DROP INDEX idx_statecolumn ON Candle');
        $this->addSql('CREATE INDEX IDX_state ON Candle (state)');
        $this->addSql('DROP INDEX idx_searchcolumn ON Candle');
        $this->addSql('CREATE INDEX IDX_search ON Candle (id, obituaryId, deletedAt, state)');
        $this->addSql('ALTER TABLE Candle ADD CONSTRAINT FK_FCEBD52C68BF794B FOREIGN KEY (obituaryId) REFERENCES Obituary (id)');
    }
}
